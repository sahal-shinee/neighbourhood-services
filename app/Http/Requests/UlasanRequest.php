<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest UlasanRequest
 *
 * Memvalidasi data yang dikirim dari form ulasan/review pelanggan.
 *
 * Catatan: validasi aturan bisnis lanjutan (pesanan harus milik pelanggan,
 * pesanan harus berstatus 'selesai', belum pernah diulas) dilakukan di
 * PelangganUlasanController::store() karena memerlukan query database.
 */
class UlasanRequest extends FormRequest
{
    /**
     * Hanya pelanggan yang bisa memberikan ulasan.
     */
    public function authorize(): bool
    {
        return $this->user()->isPelanggan();
    }

    /**
     * Aturan validasi form ulasan.
     *
     * Rating menggunakan 'between:1,5' karena harus berupa angka bulat 1–5 bintang.
     * id_pesanan diverifikasi keberadaannya di database via 'exists'.
     */
    public function rules(): array
    {
        return [
            'rating'          => ['required', 'integer', 'between:1,5'],
            'komentar_ulasan' => ['nullable', 'string', 'max:1000'],
            // Verifikasi bahwa pesanan yang diulas benar-benar ada di database
            'id_pesanan'      => ['required', 'exists:pesanan_jasa,id_pesanan'],
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'rating.required' => 'Silakan pilih rating bintang terlebih dahulu.',
            'rating.integer'  => 'Rating harus berupa angka.',
            'rating.between'  => 'Rating harus bernilai antara 1 sampai 5.',
            'komentar_ulasan.max' => 'Pengalaman Anda tidak boleh lebih dari 1000 karakter.',
        ];
    }
}
