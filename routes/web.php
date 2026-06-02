<?php

/**
 * routes/web.php — Definisi semua rute web aplikasi Neighbourhood Services
 *
 * Struktur rute:
 *
 * 1. PUBLIK (tanpa autentikasi)
 *    - GET / → LandingController@index (halaman landing page)
 *    - Rute autentikasi Breeze (login, register, logout, reset password)
 *
 * 2. TERAUTENTIKASI (middleware 'auth')
 *    - GET /dashboard → redirect ke dashboard sesuai peran
 *
 *    a. ADMIN (middleware 'role:admin', prefix '/admin', name 'admin.')
 *       Mengelola platform: verifikasi, pengguna, pesanan, kategori, laporan
 *
 *    b. PENYEDIA (middleware 'role:penyedia', prefix '/penyedia', name 'penyedia.')
 *       Mengelola jasa, pesanan masuk, jadwal, portofolio, profil
 *
 *    c. PELANGGAN (middleware 'role:pelanggan', prefix '/pelanggan', name 'pelanggan.')
 *       Mencari jasa, memesan, melihat riwayat, mengulas, melaporkan penyedia
 *
 *    d. NOTIFIKASI (semua peran terautentikasi)
 *       Tandai notifikasi sebagai dibaca
 *
 *    e. API INTERNAL
 *       Endpoint JSON untuk data jadwal (dikonsumsi form booking via AJAX)
 */

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminLaporanController;
use App\Http\Controllers\AdminPenggunaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingApiController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PelangganBookingController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganFavoritController;
use App\Http\Controllers\PelangganPesananController;
use App\Http\Controllers\PelangganUlasanController;
use App\Http\Controllers\PenyediaController;
use App\Http\Controllers\PenyediaJasaController;
use App\Http\Controllers\PenyediaPesananController;
use App\Http\Controllers\PenyediaPortofolioController;
use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

// ─── Rute Publik ─────────────────────────────────────────────────────────────

// Halaman utama yang dapat diakses siapa saja tanpa login
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Halaman statis publik (tidak perlu login)
Route::get('/syarat-ketentuan', [StaticPageController::class, 'terms'])->name('terms');
Route::get('/kebijakan-privasi', [StaticPageController::class, 'privacy'])->name('privacy');

// Rute bawaan Laravel Breeze untuk autentikasi (login, register, logout, reset password)
// Didefinisikan di routes/auth.php
require __DIR__.'/auth.php';

// ─── Rute Terautentikasi ──────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Redirect /dashboard ke dashboard sesuai peran pengguna yang login
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return redirect()->route($user->peran . '.dashboard');
    })->name('dashboard');

    // ─── Rute Admin ───────────────────────────────────────────────────────────
    // Hanya bisa diakses pengguna berperan 'admin'
    // Semua URL diawali /admin/ dan nama route diawali 'admin.'
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Verifikasi penyedia: lihat daftar pending, approve, atau reject
        Route::get('/verifikasi', [AdminController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/verifikasi/{id}/approve', [AdminController::class, 'approve'])->name('verifikasi.approve');
        Route::post('/verifikasi/{id}/reject', [AdminController::class, 'reject'])->name('verifikasi.reject');

        // Manajemen pengguna (CRUD via resource route)
        Route::resource('/pengguna', AdminPenggunaController::class);
        // Tampilkan foto KTP penyedia secara aman (private storage, hanya admin)
        Route::get('/pengguna/{id}/ktp', [AdminController::class, 'viewKtp'])->name('pengguna.ktp');

        // Daftar semua pesanan di platform
        Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('pesanan');

        // Manajemen kategori jasa (CRUD via resource route)
        Route::resource('/kategori', AdminKategoriController::class);

        // Manajemen laporan pelanggaran dan penonaktifan penyedia
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{id}', [AdminLaporanController::class, 'show'])->name('laporan.show');
        Route::patch('/laporan/{id}/status', [AdminLaporanController::class, 'updateStatus'])->name('laporan.status');
        Route::delete('/laporan/{id}', [AdminLaporanController::class, 'destroy'])->name('laporan.destroy');
        // Toggle aktif/nonaktif akun penyedia dari konteks laporan
        Route::post('/penyedia/{id}/toggle-aktif', [AdminLaporanController::class, 'toggleAktifPenyedia'])->name('penyedia.toggle-aktif');
    });

    // ─── Rute Penyedia ────────────────────────────────────────────────────────
    // Hanya bisa diakses pengguna berperan 'penyedia'
    Route::middleware('role:penyedia')->prefix('penyedia')->name('penyedia.')->group(function () {
        Route::get('/dashboard', [PenyediaController::class, 'dashboard'])->name('dashboard');

        // Profil penyedia: lihat dan perbarui
        Route::get('/profil', [PenyediaController::class, 'profil'])->name('profil');
        Route::put('/profil', [PenyediaController::class, 'updateProfil'])->name('profil.update');
        // Pengajuan banding verifikasi (jika akun ditolak admin)
        Route::post('/profil/banding', [PenyediaController::class, 'updateBanding'])->name('profil.banding');

        // CRUD jasa/layanan + toggle status aktif/nonaktif
        Route::resource('/jasa', PenyediaJasaController::class);
        Route::post('/jasa/{id}/toggle-status', [PenyediaJasaController::class, 'toggleStatus'])->name('jasa.toggle-status');

        // CRUD portofolio karya
        Route::resource('/portofolio', PenyediaPortofolioController::class);

        // Manajemen pesanan yang masuk
        Route::get('/pesanan', [PenyediaPesananController::class, 'index'])->name('pesanan.index');
        Route::post('/pesanan/{id}/setujui', [PenyediaPesananController::class, 'setujui'])->name('pesanan.setujui');
        Route::post('/pesanan/{id}/selesai', [PenyediaPesananController::class, 'selesai'])->name('pesanan.selesai');
        Route::post('/pesanan/{id}/tolak', [PenyediaPesananController::class, 'tolak'])->name('pesanan.tolak');
        Route::patch('/pesanan/{id}/estimasi', [PenyediaPesananController::class, 'updateEstimasi'])->name('pesanan.estimasi');

        // Kalender jadwal (FullCalendar)
        Route::get('/jadwal', [PenyediaController::class, 'jadwal'])->name('jadwal');
    });

    // ─── Rute Pelanggan ───────────────────────────────────────────────────────
    // Hanya bisa diakses pengguna berperan 'pelanggan'
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/dashboard', [PelangganController::class, 'dashboard'])->name('dashboard');

        // Pencarian dan temukan jasa
        Route::get('/cari', [PelangganController::class, 'cari'])->name('cari');

        // Profil publik penyedia dan ulasannya
        Route::get('/penyedia/{id}', [PelangganController::class, 'showPenyedia'])->name('penyedia.show');
        Route::get('/penyedia/{id}/ulasan', [PelangganController::class, 'ulasanPenyedia'])->name('penyedia.ulasan');

        // Proses booking (form dan submit)
        Route::get('/booking/{jasa_id}/create', [PelangganBookingController::class, 'create'])->name('booking.create');
        Route::post('/booking', [PelangganBookingController::class, 'store'])->name('booking.store');

        // Manajemen pesanan (riwayat, detail, batal, hapus riwayat, update estimasi)
        Route::get('/pesanan', [PelangganPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{id}', [PelangganPesananController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{id}/batal', [PelangganPesananController::class, 'batal'])->name('pesanan.batal');
        Route::patch('/pesanan/{id}/estimasi', [PelangganPesananController::class, 'updateEstimasi'])->name('pesanan.estimasi');
        Route::delete('/pesanan/{id}', [PelangganPesananController::class, 'destroy'])->name('pesanan.destroy');

        // Kirim ulasan (hanya POST, tidak ada halaman khusus — form ada di detail pesanan)
        Route::post('/ulasan', [PelangganUlasanController::class, 'store'])->name('ulasan.store');

        // Profil pelanggan
        Route::get('/profil', [PelangganController::class, 'profil'])->name('profil');
        Route::put('/profil', [PelangganController::class, 'updateProfil'])->name('profil.update');

        // Laporan pelanggaran ke admin
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{id}/create', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');

        // Favorit jasa
        Route::get('/favorit', [PelangganFavoritController::class, 'index'])->name('favorit.index');
        Route::post('/favorit/{jasaId}/toggle', [PelangganFavoritController::class, 'toggle'])->name('favorit.toggle');

        // Notifikasi: hapus semua
        Route::delete('/notifikasi', [NotifikasiController::class, 'hapusSemua'])->name('notifikasi.hapus-semua');
    });

    // ─── Notifikasi (semua peran terautentikasi) ──────────────────────────────
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca-semua');
    Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');

    // ─── API Internal (endpoint JSON untuk JavaScript) ────────────────────────
    // Digunakan form booking untuk mengecek jadwal penyedia yang sudah terisi
    Route::get('/api/jadwal-terpesan/{penyedia_id}', [BookingApiController::class, 'jadwalTerpesan']);
});
