<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilRequest;
use App\Models\PesananJasa;
use App\Traits\CompressesImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller PenyediaController
 *
 * Mengelola halaman-halaman utama panel penyedia jasa:
 *  - Dashboard dengan statistik kinerja dan grafik pesanan
 *  - Profil penyedia (lihat dan perbarui)
 *  - Jadwal/kalender pesanan yang disetujui
 *  - Pengajuan banding verifikasi
 */
class PenyediaController extends Controller
{
    use CompressesImages;
    /**
     * Dashboard penyedia — ringkasan kinerja dan grafik tren pesanan.
     *
     * Data statistik:
     *  - total_jasa            : jumlah layanan yang dimiliki
     *  - total_pesanan         : total pesanan yang pernah masuk
     *  - rating_rata_rata      : rata-rata bintang dari semua ulasan
     *  - pendapatan_bulan_ini  : estimasi pendapatan bulan berjalan (pesanan selesai × tarif × jam)
     */
    public function dashboard(Request $request)
    {
        $penyedia = $request->user();

        $stats = [
            'total_jasa'    => $penyedia->jasaSebagaiPenyedia()->count(),

            // Hitung total pesanan yang masuk untuk semua jasa milik penyedia ini
            'total_pesanan' => PesananJasa::whereHas('jasa', function($q) use ($penyedia) {
                $q->where('id_penyedia', $penyedia->id_pengguna);
            })->count(),

            'rating_rata_rata' => $penyedia->rating_rata_rata, // Dari accessor di model Pengguna

            // Estimasi pendapatan: pesanan selesai di bulan & tahun berjalan.
            // Kalkulasi berbeda berdasarkan tipe tarif:
            //  - paket          : harga paket yang dipilih
            //  - per_pengerjaan : tarif_per_jam (= tarif per pengerjaan untuk tipe ini)
            //  - per_jam        : durasi jam × tarif_per_jam
            'pendapatan_bulan_ini' => PesananJasa::with(['jasa', 'paket'])
                ->whereHas('jasa', function($q) use ($penyedia) {
                    $q->where('id_penyedia', $penyedia->id_pengguna);
                })->selesai()
                  ->whereMonth('tanggal_booking', now()->month)
                  ->whereYear('tanggal_booking', now()->year)
                  ->get()
                  ->sum(function($pesanan) {
                      if (!$pesanan->jasa) return 0;
                      if ($pesanan->jasa->tipe_tarif === 'paket') {
                          return $pesanan->paket ? (float) $pesanan->paket->harga : 0;
                      }
                      if ($pesanan->jasa->tipe_tarif === 'per_pengerjaan') {
                          return (float) $pesanan->jasa->tarif_per_jam;
                      }
                      // per_jam: hitung dari durasi
                      $mulai   = \Carbon\Carbon::parse($pesanan->jam_mulai);
                      $selesai = \Carbon\Carbon::parse($pesanan->jam_selesai);
                      // abs() penting: di Carbon 3 diffInHours bisa negatif tergantung urutan argumen
                      $jam     = abs($selesai->diffInHours($mulai));
                      return $jam * (float) $pesanan->jasa->tarif_per_jam;
                  })
        ];

        // Data tren pesanan per bulan untuk grafik batang di dashboard
        $monthly_orders = PesananJasa::whereHas('jasa', function($q) use ($penyedia) {
            $q->where('id_penyedia', $penyedia->id_pengguna);
        })
        ->selectRaw('MONTH(tanggal_booking) as month_num, count(*) as count')
        ->groupBy('month_num')
        ->orderBy('month_num', 'asc')
        ->get();

        $months       = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $chart_labels = [];
        $chart_values = [];

        // Jika belum ada pesanan, tampilkan data dummy untuk demo
        if ($monthly_orders->isEmpty()) {
            $chart_labels = ['Maret', 'April', 'Mei'];
            $chart_values = [3, 8, max(4, $stats['total_pesanan'])];
        } else {
            foreach ($monthly_orders as $data) {
                $chart_labels[] = $months[$data->month_num - 1] ?? 'Bulan';
                $chart_values[] = $data->count;
            }
        }

        return view('penyedia.dashboard', compact('stats', 'chart_labels', 'chart_values'));
    }

    /**
     * Tampilkan halaman profil penyedia yang sedang login.
     */
    public function profil(Request $request)
    {
        $user = $request->user();
        return view('penyedia.profil', compact('user'));
    }

    /**
     * Simpan perubahan data profil penyedia.
     *
     * Proses upload foto profil:
     *  1. Hapus foto lama dari storage (jika ada) untuk menghemat ruang
     *  2. Simpan foto baru ke folder 'profil' di disk 'public'
     */
    public function updateProfil(ProfilRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // Proses upload foto profil baru jika ada file yang dikirim
        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $this->simpanGambarTerkompres($request->file('foto_profil'), 'profil');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Tampilkan halaman kalender jadwal penyedia.
     *
     * Hanya menampilkan pesanan dengan status 'disetujui' karena:
     *  - 'menunggu' belum tentu jadi
     *  - 'selesai' dan 'dibatalkan' tidak perlu ditampilkan di kalender
     * Data pesanan dikirim ke view untuk di-render oleh FullCalendar (JavaScript library).
     */
    public function jadwal(Request $request)
    {
        $penyedia = $request->user();

        // Ambil pesanan yang disetujui sebagai event kalender
        $pesanan = PesananJasa::whereHas('jasa', function($q) use ($penyedia) {
            $q->where('id_penyedia', $penyedia->id_pengguna);
        })->disetujui()->get();

        return view('penyedia.jadwal', compact('pesanan'));
    }

    /**
     * Proses pengajuan banding verifikasi oleh penyedia yang ditolak.
     *
     * Saat banding diajukan:
     *  - Pesan banding disimpan untuk dibaca admin
     *  - Status verifikasi dikembalikan ke 'pending' agar admin bisa meninjau ulang
     *  - Foto KTP baru bisa diunggah sebagai bukti tambahan (opsional)
     */
    public function updateBanding(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'pesan_banding' => ['required', 'string', 'max:1000'],
            'foto_ktp'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
        ]);

        $data = [
            'pesan_banding'     => $request->input('pesan_banding'),
            'status_verifikasi' => 'pending', // Reset ke pending agar admin bisa meninjau ulang
        ];

        // Upload foto KTP baru ke private disk jika disertakan dalam pengajuan banding
        if ($request->hasFile('foto_ktp')) {
            if ($user->foto_ktp) {
                Storage::disk('local')->delete($user->foto_ktp);
            }
            $data['foto_ktp'] = $this->simpanGambarTerkompres($request->file('foto_ktp'), 'ktp', 'local', 1600, 85);
        }

        $user->update($data);

        return back()->with('success', 'Pengajuan banding berhasil dikirimkan. Akun Anda sedang ditinjau kembali oleh Admin.');
    }
}
