<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller NewPasswordController
 *
 * Mengelola proses penetapan password baru setelah pengguna mengklik tautan reset.
 *
 * Alur:
 *  1. Pengguna mengklik tautan reset di email → diarahkan ke halaman ini
 *     (URL mengandung token dan email sebagai query string)
 *  2. Pengguna mengisi password baru dan konfirmasinya
 *  3. Sistem memverifikasi token (valid & belum kedaluwarsa)
 *  4. Jika valid, password diperbarui dan token dihapus dari database
 *  5. Pengguna diarahkan ke halaman login dengan pesan sukses
 */
class NewPasswordController extends Controller
{
    /**
     * Tampilkan halaman form reset password.
     * Token dan email diteruskan ke view melalui object Request.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Proses penetapan password baru.
     *
     * Validasi yang dilakukan:
     *  - token   : wajib ada (dari URL)
     *  - email   : wajib, format email
     *  - password: wajib, harus cocok dengan konfirmasi, ikuti aturan Password::defaults()
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi data yang dikirim dari form reset password
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Proses reset password melalui Password broker.
        // Jika token valid, callback akan dipanggil untuk mengubah password pengguna.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Pengguna $user) use ($request) {
                // Perbarui password dengan yang baru (di-hash oleh cast 'hashed')
                // dan buat remember_token baru untuk keamanan
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Trigger event PasswordReset — berguna untuk invalidasi sesi lain
                event(new PasswordReset($user));
            }
        );

        // Evaluasi hasil reset:
        // - PASSWORD_RESET : berhasil → redirect ke login dengan pesan sukses
        // - Selain itu     : gagal (token invalid/kedaluwarsa) → kembali dengan error
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
