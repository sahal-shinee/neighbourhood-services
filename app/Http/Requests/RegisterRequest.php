<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FormRequest RegisterRequest
 *
 * Memvalidasi data yang dikirim dari form registrasi akun baru.
 *
 * Aturan bisnis yang dikodekan di sini:
 *  - Email harus unik di tabel 'pengguna' (tidak boleh ada akun duplikat)
 *  - Peran hanya boleh 'pelanggan' atau 'penyedia' (admin tidak bisa daftar sendiri)
 *  - Foto KTP WAJIB diunggah jika mendaftar sebagai penyedia
 *    (karena penyedia memerlukan verifikasi identitas oleh admin)
 *  - Password minimal 8 karakter dan harus diisi dua kali untuk konfirmasi
 */
class RegisterRequest extends FormRequest
{
    /**
     * Semua orang boleh melakukan registrasi (tidak perlu autentikasi dulu).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi form registrasi.
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email'        => ['required', 'string', 'email', 'max:150', 'unique:pengguna,email'],
            'no_telepon'   => ['required', 'string', 'max:20'],
            'alamat'       => ['required', 'string'],
            // Peran hanya boleh salah satu dari dua nilai ini
            'peran'        => ['required', Rule::in(['pelanggan', 'penyedia'])],
            // foto_ktp wajib ada JIKA peran yang dipilih adalah 'penyedia'
            'foto_ktp'     => ['required_if:peran,penyedia', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'password'     => ['required', 'confirmed', 'min:8'],
        ];
    }

    /**
     * Pesan error kustom untuk validasi tertentu.
     * Menggantikan pesan default Laravel dengan bahasa yang lebih natural.
     */
    public function messages(): array
    {
        return [
            // Pesan khusus untuk required_if agar lebih informatif
            'foto_ktp.required_if' => 'Foto KTP wajib diunggah untuk pendaftaran sebagai Penyedia Jasa.',
        ];
    }
}
