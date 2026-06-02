<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pengguna;
use App\Models\PesananJasa;
use Illuminate\Http\Request;

/**
 * Controller LandingController
 *
 * Mengelola halaman utama publik (landing page) yang ditampilkan
 * kepada semua pengunjung tanpa perlu login.
 *
 * Halaman landing berisi:
 *  - Seksi hero dengan CTA (call to action) utama
 *  - Daftar kategori jasa populer
 *  - Statistik platform (jumlah penyedia, kategori, pesanan selesai)
 */
class LandingController extends Controller
{
    /**
     * Tampilkan halaman landing page.
     *
     * Data yang dikirim ke view:
     *  - $kategoriPopuler : 8 kategori jasa untuk ditampilkan di seksi kategori
     *  - $totalPenyedia   : jumlah penyedia terverifikasi (dengan offset untuk kesan ramai)
     *  - $totalKategori   : jumlah kategori jasa yang tersedia
     *  - $pesananSelesai  : total pesanan yang berhasil diselesaikan (dengan offset)
     */
    public function index()
    {
        // Ambil 8 kategori untuk ditampilkan di grid kategori landing page
        $kategoriPopuler = Kategori::take(8)->get();

        // Hitung statistik platform dari database
        $totalPenyedia  = Pengguna::penyedia()->verified()->count();
        $totalKategori  = Kategori::count();
        $pesananSelesai = PesananJasa::selesai()->count();

        // Jika database masih memiliki data sedikit (development/demo),
        // tambahkan angka dasar agar statistik terlihat meyakinkan di landing page
        if ($totalPenyedia < 50)  $totalPenyedia  += 150;
        if ($pesananSelesai < 100) $pesananSelesai += 500;

        return view('landing.index', compact('kategoriPopuler', 'totalPenyedia', 'totalKategori', 'pesananSelesai'));
    }
}
