<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Pengguna;
use App\Models\PesananJasa;
use App\Notifications\LaporanDihapusNotification;
use App\Notifications\PesananDitolakNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller AdminLaporanController
 *
 * Mengelola semua aksi admin terkait laporan pelanggaran yang diajukan pelanggan.
 *
 * Fitur yang tersedia:
 *  1. Melihat daftar laporan dengan filter status
 *  2. Melihat detail laporan beserta informasi pelapor dan penyedia
 *  3. Memperbarui status laporan dan menambahkan catatan tindakan
 *  4. Menghapus laporan secara permanen (termasuk file bukti foto)
 *  5. Toggle aktif/nonaktif akun penyedia yang dilaporkan
 */
class AdminLaporanController extends Controller
{
    /**
     * Daftar semua laporan dengan filter status opsional.
     *
     * Filter status: baru | ditinjau | ditindaklanjuti | ditolak
     * Diurutkan terbaru di atas, dipaginasi 15 per halaman.
     */
    public function index(Request $request)
    {
        // Eager load relasi untuk menghindari N+1 query problem di tabel
        $query = Laporan::with(['pelapor', 'penyedia', 'pesanan']);

        // Terapkan filter status jika ada di query string
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporan = $query->latest()->paginate(15)->withQueryString();

        return view('admin.laporan.index', compact('laporan'));
    }

    /**
     * Tampilkan detail satu laporan beserta informasi lengkap.
     * Termasuk: isi laporan, data pelapor, data penyedia, pesanan terkait.
     */
    public function show($id)
    {
        // Load relasi mendalam: pesanan → jasa untuk menampilkan nama layanan terkait
        $laporan = Laporan::with(['pelapor', 'penyedia', 'pesanan.jasa'])->findOrFail($id);
        return view('admin.laporan.show', compact('laporan'));
    }

    /**
     * Perbarui status laporan dan tambahkan catatan tindakan admin.
     *
     * Digunakan saat admin mengambil keputusan:
     *  - 'ditinjau'        : sedang diproses
     *  - 'ditindaklanjuti' : terbukti, tindakan sudah diambil
     *  - 'ditolak'         : tidak terbukti, laporan ditolak
     */
    public function updateStatus(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        // Validasi data: status harus dari enum yang valid, catatan maksimal 1000 karakter
        $data = $request->validate([
            'status'        => ['required', 'in:baru,ditinjau,ditindaklanjuti,ditolak'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $laporan->update($data);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Hapus laporan secara permanen dari database.
     *
     * Proses:
     *  1. Hapus file bukti foto dari storage (jika ada) agar tidak ada file orphan
     *  2. Hapus record laporan dari database
     *  3. Redirect ke daftar laporan
     */
    public function destroy($id)
    {
        $laporan = Laporan::with('pelapor', 'penyedia')->findOrFail($id);

        // Kirim notifikasi ke pelapor jika laporan belum diselesaikan (baru/ditinjau)
        // agar pelanggan tidak bingung kenapa laporannya tiba-tiba hilang.
        if (in_array($laporan->status, ['baru', 'ditinjau']) && $laporan->pelapor) {
            $laporan->pelapor->notify(new LaporanDihapusNotification(
                $laporan->alasan,
                $laporan->penyedia?->nama_lengkap ?? 'penyedia'
            ));
        }

        // Hapus file bukti foto dari disk 'public' jika ada
        if ($laporan->bukti_foto) {
            Storage::disk('public')->delete($laporan->bukti_foto);
        }

        $laporan->delete();

        return redirect()->route('admin.laporan.index')
            ->with('success', 'Laporan berhasil dihapus secara permanen.');
    }

    /**
     * Toggle status aktif/nonaktif akun penyedia yang dilaporkan.
     *
     * Menonaktifkan: penyedia tidak bisa login, jasa tidak ditampilkan di pencarian.
     * Mengaktifkan kembali: akun berfungsi normal seperti sebelumnya.
     *
     * Diakses dari halaman detail laporan sebagai tindakan lanjutan admin.
     */
    public function toggleAktifPenyedia($id)
    {
        $penyedia = Pengguna::where('peran', 'penyedia')->findOrFail($id);
        $newAktif = ! $penyedia->is_aktif;

        // Jika dinonaktifkan: cari dan batalkan semua pesanan aktif milik penyedia ini.
        // Ini mencegah pelanggan menunggu penyedia yang sudah diblokir.
        if (! $newAktif) {
            $pesananAktif = PesananJasa::whereHas('jasa', function ($q) use ($penyedia) {
                $q->where('id_penyedia', $penyedia->id_pengguna);
            })->whereIn('status_pesanan', ['menunggu', 'disetujui'])->get();

            foreach ($pesananAktif as $pesanan) {
                $pesanan->update(['status_pesanan' => 'dibatalkan']);
                // Beri tahu setiap pelanggan yang terdampak
                $pesanan->pelanggan->notify(new PesananDitolakNotification($pesanan->load('jasa')));
            }
        }

        $penyedia->update(['is_aktif' => $newAktif]);

        $msg = $newAktif
            ? "Akun penyedia {$penyedia->nama_lengkap} telah diaktifkan kembali."
            : "Akun penyedia {$penyedia->nama_lengkap} telah dinonaktifkan dan pesanan aktifnya dibatalkan.";

        return back()->with('success', $msg);
    }
}
