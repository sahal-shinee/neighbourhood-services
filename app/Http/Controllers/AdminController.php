<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\PesananJasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller AdminController
 *
 * Mengelola fitur-fitur utama panel admin:
 *  - Dashboard dengan statistik dan grafik
 *  - Halaman verifikasi penyedia baru
 *  - Aksi persetujuan / penolakan penyedia
 *  - Daftar semua pesanan di platform
 */
class AdminController extends Controller
{
    /**
     * Dashboard admin — statistik ringkas dan grafik tren pesanan.
     *
     * Data yang dikirim ke view:
     *  - $stats         : 4 angka statistik utama (total pengguna, pending, dll)
     *  - $role_stats    : distribusi peran untuk grafik donut
     *  - $chart_labels  : label bulan untuk grafik garis tren pesanan
     *  - $chart_values  : jumlah pesanan per bulan untuk grafik garis
     *  - $recent_activity: 5 pengguna terbaru yang mendaftar
     */
    public function dashboard()
    {
        // Kumpulkan 4 statistik utama untuk kartu ringkasan di bagian atas dashboard
        $stats = [
            'total_pengguna'      => Pengguna::count(),
            'pending_verifikasi'  => Pengguna::penyedia()->where('status_verifikasi', 'pending')->count(),
            'pesanan_hari_ini'    => PesananJasa::whereDate('created_at', today())->count(),
            'penyedia_aktif'      => Pengguna::penyedia()->verified()->count(),
        ];

        // Data distribusi peran untuk grafik donut "Sebaran Pengguna"
        $role_stats = [
            'pelanggan' => Pengguna::pelanggan()->count(),
            'penyedia'  => Pengguna::penyedia()->count(),
            'admin'     => Pengguna::where('peran', 'admin')->count(),
        ];

        // Query jumlah pesanan per bulan untuk grafik garis "Tren Pesanan"
        // MONTH() mengekstrak nomor bulan dari tanggal pembuatan pesanan
        $monthly_data = PesananJasa::selectRaw('MONTH(created_at) as month_num, count(*) as count')
            ->groupBy('month_num')
            ->orderBy('month_num', 'asc')
            ->get();

        $months        = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $chart_labels  = [];
        $chart_values  = [];

        if ($monthly_data->isEmpty()) {
            // Jika database masih kosong, tampilkan data dummy untuk demo
            $chart_labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei'];
            $chart_values = [8, 15, 22, 34, max(12, $stats['pesanan_hari_ini'] + 10)];
        } else {
            // Konversi nomor bulan (1–12) ke nama bulan dalam Bahasa Indonesia
            foreach ($monthly_data as $data) {
                $chart_labels[] = $months[$data->month_num - 1] ?? 'Bulan';
                $chart_values[] = $data->count;
            }
        }

        // 5 pengguna terbaru untuk widget "Aktivitas Terkini"
        $recent_activity = Pengguna::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_activity', 'role_stats', 'chart_labels', 'chart_values'));
    }

    /**
     * Sajikan foto KTP penyedia secara aman — hanya untuk admin.
     *
     * KTP disimpan di private disk (storage/app/private/ktp/) sehingga
     * tidak bisa diakses langsung via URL publik. Controller ini menjadi
     * "gerbang" yang memverifikasi bahwa yang mengakses adalah admin,
     * lalu mengalirkan file ke browser sebagai inline image.
     *
     * Rute: GET /admin/pengguna/{id}/ktp → admin.pengguna.ktp
     */
    public function viewKtp($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        // Pastikan foto KTP ada di database dan file-nya ada di storage
        if (!$pengguna->foto_ktp || !Storage::disk('local')->exists($pengguna->foto_ktp)) {
            abort(404, 'Foto KTP tidak ditemukan.');
        }

        // Alirkan file ke browser sebagai inline image (tampil langsung, bukan download)
        return Storage::disk('local')->response($pengguna->foto_ktp);
    }

    /**
     * Halaman daftar penyedia yang menunggu verifikasi.
     * Hanya menampilkan penyedia berstatus 'pending'.
     */
    public function verifikasi()
    {
        $penyedia_pending = Pengguna::penyedia()->where('status_verifikasi', 'pending')->latest()->get();
        return view('admin.verifikasi.index', compact('penyedia_pending'));
    }

    /**
     * Setujui (approve) permohonan verifikasi penyedia.
     * Status berubah menjadi 'diverifikasi' dan pesan banding dihapus (jika ada).
     */
    public function approve($id)
    {
        $penyedia = Pengguna::findOrFail($id);
        $penyedia->update([
            'status_verifikasi' => 'diverifikasi',
            'pesan_banding'     => null, // Bersihkan pesan banding setelah berhasil disetujui
        ]);
        return back()->with('success', 'Penyedia jasa berhasil diverifikasi.');
    }

    /**
     * Tolak (reject) permohonan verifikasi penyedia.
     * Status berubah menjadi 'ditolak' — penyedia dapat mengajukan banding.
     */
    public function reject($id)
    {
        $penyedia = Pengguna::findOrFail($id);
        $penyedia->update(['status_verifikasi' => 'ditolak']);
        return back()->with('warning', 'Penyedia jasa telah ditolak.');
    }

    /**
     * Daftar semua pesanan di platform dengan filter status opsional.
     * Admin dapat melihat pesanan dari semua penyedia dan pelanggan.
     */
    public function pesanan(Request $request)
    {
        // Eager load relasi untuk menghindari N+1 query problem
        $query = PesananJasa::with(['pelanggan', 'jasa.penyedia']);

        // Terapkan filter status jika dipilih di UI
        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        $pesanan = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pesanan.index', compact('pesanan'));
    }
}
