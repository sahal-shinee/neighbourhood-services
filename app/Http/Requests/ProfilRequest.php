<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FormRequest ProfilRequest
 *
 * Memvalidasi data yang dikirim dari form edit profil pengguna.
 * Digunakan oleh PelangganController dan PenyediaController.
 *
 * Catatan khusus validasi email:
 *  Menggunakan Rule::unique()->ignore() agar pengguna bisa menyimpan profil
 *  tanpa mengubah email (validasi 'unique' tidak akan gagal jika emailnya
 *  sama dengan email mereka sendiri saat ini).
 */
class ProfilRequest extends FormRequest
{
    /**
     * Pengguna yang sedang login selalu boleh mengupdate profilnya sendiri.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi form profil.
     */
    public function rules(): array
    {
        // Ambil ID pengguna yang sedang login untuk dikecualikan dari cek unique email
        $userId = $this->user()->id_pengguna;

        return [
            'nama_lengkap' => ['required', 'string', 'max:100'],
            // Email harus unik di kolom 'email' tabel 'pengguna',
            // KECUALI untuk baris dengan id_pengguna = $userId (milik dirinya sendiri)
            'email'        => ['required', 'string', 'email', 'max:150', Rule::unique('pengguna', 'email')->ignore($userId, 'id_pengguna')],
            'no_telepon'   => ['required', 'string', 'max:20'],
            'alamat'       => ['required', 'string'],
            // Koordinat GPS opsional — diisi dari browser geolocation API
            'latitude'     => ['nullable', 'numeric'],
            'longitude'    => ['nullable', 'numeric'],
            // Foto profil opsional, hanya jika ingin mengganti foto lama
            'foto_profil'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
