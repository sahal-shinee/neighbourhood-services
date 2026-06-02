<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 *
 * Bertanggung jawab untuk dua hal sekaligus dalam setiap request yang memerlukan otorisasi:
 *
 * 1. PEMERIKSAAN DEAKTIVASI MID-SESSION
 *    Jika admin menonaktifkan akun saat pengguna sedang login,
 *    middleware ini akan mendeteksi hal tersebut dan memaksa logout secara langsung.
 *    Ini mencegah pengguna yang diblokir terus mengakses sistem sampai sesi habis.
 *
 * 2. PEMERIKSAAN PERAN (ROLE-BASED ACCESS CONTROL)
 *    Memastikan pengguna yang login memiliki peran yang sesuai
 *    untuk mengakses rute tertentu (admin, penyedia, atau pelanggan).
 *
 * Cara penggunaan di routes/web.php:
 *   Route::middleware('role:admin')->group(...)
 *   Route::middleware('role:penyedia')->group(...)
 *   Route::middleware('role:pelanggan')->group(...)
 */
class CheckRole
{
    /**
     * Tangani setiap request yang masuk.
     *
     * @param string ...$roles Daftar peran yang diizinkan, diterima dari parameter route middleware
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan pengguna sudah login — jika belum, arahkan ke halaman login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Periksa apakah akun pengguna sudah dinonaktifkan oleh admin saat sesi berjalan.
        // Jika iya, paksa logout dan hapus sesi serta token CSRF agar sesi benar-benar bersih.
        if (! $request->user()->is_aktif) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();       // Hapus seluruh data sesi
            $request->session()->regenerateToken();  // Buat ulang token CSRF baru

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh admin. Hubungi administrator untuk informasi lebih lanjut.']);
        }

        // Periksa apakah peran pengguna termasuk dalam daftar peran yang diizinkan.
        // Contoh: route 'role:admin' → $roles = ['admin'] → hanya admin yang boleh akses.
        if (! in_array($request->user()->peran, $roles)) {
            abort(403, 'Akses ditolak. Halaman ini tidak tersedia untuk peran Anda.');
        }

        // Semua pemeriksaan lolos — lanjutkan request ke controller
        return $next($request);
    }
}
