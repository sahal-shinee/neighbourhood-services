<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * FormRequest LoginRequest
 *
 * Menangani validasi dan autentikasi dari form login.
 *
 * Fitur keamanan yang diimplementasikan:
 *  1. RATE LIMITING: Membatasi percobaan login maksimal 5 kali per IP+email.
 *     Setelah 5x gagal, pengguna harus menunggu sebelum bisa mencoba lagi.
 *     Ini mencegah serangan brute force password.
 *
 *  2. "INGAT SAYA" (Remember Me): Jika checkbox 'remember' dicentang,
 *     token remember_token disimpan di cookie pengguna selama beberapa hari,
 *     sehingga tidak perlu login ulang tiap kali browser dibuka.
 */
class LoginRequest extends FormRequest
{
    /**
     * Otorisasi: semua orang boleh melakukan request login (tidak perlu autentikasi dulu).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi form login.
     * Email dan password wajib diisi dengan format yang benar.
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Proses autentikasi pengguna berdasarkan kredensial yang dikirim.
     *
     * Alur:
     *  1. Cek rate limit — tolak jika terlalu banyak percobaan gagal
     *  2. Coba login dengan Auth::attempt()
     *  3. Jika gagal: tambahkan hitungan percobaan, lempar error
     *  4. Jika berhasil: reset hitungan percobaan
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        // Pastikan pengguna belum melampaui batas percobaan login
        $this->ensureIsNotRateLimited();

        // Coba autentikasi dengan email, password, dan opsi "ingat saya"
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Login gagal: catat satu percobaan gagal untuk rate limiting
            RateLimiter::hit($this->throttleKey());

            // Lempar error validasi dengan pesan dari lang/id/auth.php
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Login berhasil: reset counter rate limiting untuk kombinasi IP+email ini
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Cek apakah pengguna sudah melampaui batas percobaan login (rate limiting).
     *
     * Batas: 5 percobaan gagal per kombinasi (email + IP address).
     * Jika terlampaui: trigger event Lockout, lempar error dengan sisa waktu tunggu.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return; // Belum mencapai batas, lanjutkan
        }

        // Trigger event Lockout untuk logging atau notifikasi (opsional)
        event(new Lockout($this));

        // Hitung sisa detik sebelum bisa mencoba lagi
        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Lempar pesan error dengan info waktu tunggu dari lang/id/auth.php
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Buat kunci unik untuk rate limiting berdasarkan kombinasi email + IP.
     *
     * Str::transliterate() memastikan karakter non-ASCII (aksara, emoji) dikonversi
     * agar tidak menyebabkan masalah saat disimpan sebagai key di cache.
     *
     * Contoh kunci: "user@example.com|127.0.0.1"
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
