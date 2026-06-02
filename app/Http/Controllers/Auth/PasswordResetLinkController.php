<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller PasswordResetLinkController
 *
 * Mengelola pengiriman tautan reset password ke email pengguna.
 *
 * Alur:
 *  1. Pengguna mengisi email di form "Lupa Sandi"
 *  2. Sistem memverifikasi email terdaftar (via Password broker)
 *  3. Jika ada, sistem mengirim email berisi tautan reset password
 *  4. Tautan mengandung token unik yang berlaku dalam waktu terbatas
 *
 * Catatan development:
 *  Konfigurasi MAIL_MAILER=log di .env menyebabkan email ditulis ke file log
 *  (storage/logs/laravel.log) bukan dikirim ke email sungguhan.
 *  Cari baris "Reset Password Notification" di log untuk menemukan tautan reset.
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Tampilkan halaman form permintaan reset password.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses permintaan pengiriman tautan reset password.
     *
     * Laravel Password broker secara otomatis:
     *  - Mencari pengguna berdasarkan email
     *  - Membuat token reset yang tersimpan di tabel password_reset_tokens
     *  - Mengirim email dengan tautan yang mengandung token tersebut
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi: email wajib diisi dan dalam format yang benar
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Kirim tautan reset password melalui Password broker Laravel.
        // Broker dikonfigurasi untuk menggunakan model Pengguna via config/auth.php.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Evaluasi hasil pengiriman:
        // - RESET_LINK_SENT : berhasil → tampilkan pesan sukses
        // - Selain itu      : gagal (email tidak ditemukan, throttle, dll) → tampilkan error
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
