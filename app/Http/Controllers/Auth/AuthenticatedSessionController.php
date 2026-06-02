<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller AuthenticatedSessionController
 *
 * Mengelola proses autentikasi (login dan logout) pengguna.
 *
 * Fitur tambahan yang diimplementasikan di luar bawaan Laravel:
 *  1. Pemeriksaan akun dinonaktifkan (is_aktif = false) sebelum mengizinkan masuk
 *  2. Redirect otomatis ke dashboard sesuai peran pengguna setelah login berhasil
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman form login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses data login yang dikirim dari form.
     *
     * Alur:
     *  1. Validasi dan autentikasi kredensial via LoginRequest
     *  2. Cek apakah akun sudah dinonaktifkan oleh admin
     *  3. Regenerasi ID sesi untuk keamanan (mencegah session fixation)
     *  4. Redirect ke dashboard sesuai peran: admin/penyedia/pelanggan
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi pengguna — akan throw ValidationException jika gagal
        $request->authenticate();

        $user = $request->user();

        // Blokir akun yang sudah dinonaktifkan admin.
        // Meskipun kredensial benar, akun yang diblokir tidak boleh masuk.
        // Proses: logout, hapus sesi, buat token baru, kembalikan error ke form.
        if (! $user->is_aktif) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan oleh admin. Hubungi administrator untuk informasi lebih lanjut.',
            ]);
        }

        // Regenerasi ID sesi untuk mencegah session fixation attack
        $request->session()->regenerate();

        // Redirect ke dashboard sesuai peran pengguna yang berhasil login
        // Contoh: peran 'pelanggan' → route 'pelanggan.dashboard'
        return redirect()->route($user->peran . '.dashboard');
    }

    /**
     * Proses logout pengguna.
     *
     * Alur pembersihan sesi:
     *  1. Logout dari guard 'web' (hapus data autentikasi)
     *  2. Invalidasi sesi (hapus semua data sesi)
     *  3. Regenerasi token CSRF (keamanan form berikutnya)
     *  4. Redirect ke halaman landing (halaman publik)
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Hapus informasi autentikasi dari sesi
        Auth::guard('web')->logout();

        // Hapus seluruh data sesi yang tersimpan
        $request->session()->invalidate();

        // Buat token CSRF baru agar form berikutnya tetap aman
        $request->session()->regenerateToken();

        // Arahkan ke halaman utama (landing page)
        return redirect('/');
    }
}
