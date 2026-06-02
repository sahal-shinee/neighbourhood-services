<?php

namespace App\Http\Controllers;

use App\Models\PesananJasa;
use App\Notifications\PesananDisetujuiNotification;
use App\Notifications\PesananDitolakNotification;
use App\Notifications\PesananSelesaiNotification;
use Illuminate\Http\Request;

/**
 * Controller PenyediaPesananController
 *
 * Mengelola aksi penyedia terhadap pesanan yang masuk untuk jasanya.
 *
 * Alur pesanan dari sisi penyedia:
 *  1. Pesanan masuk berstatus 'menunggu' → penyedia menerima notifikasi
 *  2. Penyedia dapat: menyetujui (setujui) atau menolak (tolak)
 *  3. Jika disetujui → pesanan bentrok di jam yang sama otomatis dibatalkan
 *  4. Setelah pekerjaan selesai → penyedia menandai 'selesai'
 *  5. Pelanggan mendapat notifikasi di setiap perubahan status
 */
class PenyediaPesananController extends Controller
{
    /**
     * Daftar pesanan yang masuk untuk semua jasa milik penyedia.
     * Mendukung filter berdasarkan status pesanan.
     */
    public function index(Request $request)
    {
        $penyedia = $request->user();

        // Query pesanan yang dimiliki jasa-jasa milik penyedia ini
        $pesanan = PesananJasa::with(['pelanggan', 'jasa'])
            ->whereHas('jasa', function($q) use ($penyedia) {
                $q->where('id_penyedia', $penyedia->id_pengguna);
            });

        // Terapkan filter status jika dipilih di UI
        if ($request->filled('status')) {
            $pesanan->where('status_pesanan', $request->status);
        }

        if ($request->filled('dari')) {
            $pesanan->whereDate('tanggal_booking', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $pesanan->whereDate('tanggal_booking', '<=', $request->sampai);
        }

        $pesanan = $pesanan->latest()->paginate(10)->withQueryString();

        return view('penyedia.pesanan.index', compact('pesanan'));
    }

    /**
     * Setujui pesanan yang masuk.
     *
     * Fitur penting — Auto-cancel konflik jadwal:
     *  Setelah persetujuan, sistem secara otomatis mencari pesanan lain
     *  dari penyedia yang sama pada tanggal dan jam yang bertabrakan.
     *  Pesanan yang bentrok otomatis dibatalkan dan pelanggannya diberi notifikasi.
     *
     *  Logika deteksi tumpang tindih waktu:
     *   jam_mulai pesanan baru < jam_selesai pesanan lain (A sudah mulai sebelum B berakhir)
     *   DAN jam_selesai pesanan baru > jam_mulai pesanan lain (A belum selesai saat B mulai)
     */
    public function setujui(Request $request, $id)
    {
        $pesanan = $this->getPesanan($request, $id);

        // Ubah status pesanan ke 'disetujui'
        $pesanan->update(['status_pesanan' => 'disetujui']);

        // Cari pesanan lain yang jadwalnya bertabrakan (belum disetujui/masih menunggu)
        $conflicting = PesananJasa::whereHas('jasa', function($q) use ($request) {
                $q->where('id_penyedia', $request->user()->id_pengguna);
            })
            ->where('id_pesanan', '!=', $pesanan->id_pesanan)    // Kecualikan pesanan yang baru disetujui
            ->where('status_pesanan', 'menunggu')
            ->where('tanggal_booking', $pesanan->tanggal_booking) // Hari yang sama
            ->where('jam_mulai', '<', $pesanan->jam_selesai)      // Overlap condition: mulai sebelum berakhir
            ->where('jam_selesai', '>', $pesanan->jam_mulai)      // Overlap condition: belum selesai saat mulai
            ->get();

        // Batalkan semua pesanan yang bentrok dan beri tahu pelanggannya
        foreach ($conflicting as $conflict) {
            $conflict->update(['status_pesanan' => 'dibatalkan']);
            $conflict->pelanggan->notify(new PesananDitolakNotification($conflict));
        }

        // Kirim notifikasi ke pelanggan yang pesanannya disetujui
        $pesanan->pelanggan->notify(new PesananDisetujuiNotification($pesanan));

        $extraMsg = $conflicting->count() > 0 ? " {$conflicting->count()} pesanan bentrok otomatis dibatalkan." : '';
        return back()->with('success', 'Pesanan berhasil disetujui.' . $extraMsg);
    }

    /**
     * Tandai pesanan sebagai selesai dikerjakan.
     * Memicu notifikasi ke pelanggan untuk memberikan ulasan.
     */
    public function selesai(Request $request, $id)
    {
        $pesanan = $this->getPesanan($request, $id);
        $pesanan->update(['status_pesanan' => 'selesai']);

        // Beritahu pelanggan bahwa pekerjaan sudah selesai, ajak untuk mengulas
        $pesanan->pelanggan->notify(new PesananSelesaiNotification($pesanan));

        return back()->with('success', 'Pesanan ditandai sebagai selesai.');
    }

    /**
     * Tolak/batalkan pesanan (dari sisi penyedia).
     * Menggunakan status 'dibatalkan' (sama dengan pembatalan oleh pelanggan).
     */
    public function tolak(Request $request, $id)
    {
        $pesanan = $this->getPesanan($request, $id);
        $pesanan->update(['status_pesanan' => 'dibatalkan']);

        // Beritahu pelanggan bahwa pesanannya ditolak
        $pesanan->pelanggan->notify(new PesananDitolakNotification($pesanan));

        return back()->with('warning', 'Pesanan telah dibatalkan/ditolak.');
    }

    /**
     * Perbarui estimasi hari pengerjaan untuk pesanan bertipe 'paket'.
     *
     * Pembatasan:
     *  - Hanya berlaku untuk jasa bertipe 'paket' (per_jam tidak memiliki estimasi hari)
     *  - Hanya bisa diubah selama pesanan masih aktif (menunggu/disetujui)
     */
    public function updateEstimasi(Request $request, $id)
    {
        $pesanan = $this->getPesanan($request, $id);

        // Validasi: estimasi hanya berlaku untuk tipe paket
        if ($pesanan->jasa->tipe_tarif !== 'paket') {
            return back()->with('error', 'Estimasi hanya berlaku untuk pesanan bertipe Paket.');
        }

        // Validasi: pesanan sudah selesai/dibatalkan tidak bisa diubah estimasinya
        if (!in_array($pesanan->status_pesanan, ['menunggu', 'disetujui'])) {
            return back()->with('error', 'Pesanan sudah selesai atau dibatalkan.');
        }

        $request->validate([
            'estimasi_hari' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $pesanan->update(['estimasi_hari' => $request->estimasi_hari]);

        return back()->with('success', 'Estimasi hari pengerjaan berhasil diperbarui.');
    }

    /**
     * Helper privat: ambil pesanan dengan memverifikasi kepemilikan.
     *
     * Memastikan penyedia hanya bisa mengakses pesanan dari jasa miliknya sendiri,
     * bukan pesanan dari penyedia lain (keamanan data).
     */
    private function getPesanan(Request $request, $id)
    {
        $penyediaId = $request->user()->id_pengguna;
        return PesananJasa::whereHas('jasa', function($q) use ($penyediaId) {
            $q->where('id_penyedia', $penyediaId);
        })->findOrFail($id);
    }
}
