<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest BookingRequest
 *
 * Memvalidasi data yang dikirim dari form pembuatan pesanan (booking).
 *
 * Kompleksitas utama: form booking mendukung DUA tipe data berbeda
 * dalam satu form (jam-based vs paket-based), sehingga aturan validasi
 * bersifat kondisional (required_unless / required_if).
 *
 * Aturan per tipe tarif:
 *  Tipe per_jam/per_pengerjaan:
 *   - jam_mulai  : WAJIB
 *   - jam_selesai: WAJIB, harus setelah jam_mulai
 *   - id_paket   : TIDAK digunakan
 *   - estimasi_hari: TIDAK digunakan
 *
 *  Tipe paket:
 *   - id_paket   : WAJIB (pilih salah satu paket yang tersedia)
 *   - estimasi_hari: WAJIB (berapa hari perkiraan pengerjaan)
 *   - jam_mulai  : TIDAK digunakan
 *   - jam_selesai: TIDAK digunakan
 */
class BookingRequest extends FormRequest
{
    /**
     * Hanya pelanggan yang boleh membuat pesanan (bukan penyedia atau admin).
     */
    public function authorize(): bool
    {
        return $this->user()->isPelanggan();
    }

    /**
     * Aturan validasi form booking dengan kondisional berdasarkan tipe tarif.
     */
    public function rules(): array
    {
        return [
            'jasa_id'         => ['required', 'exists:jasa,id_jasa'],
            'tipe_tarif'      => ['required', 'in:per_jam,per_pengerjaan,paket'],
            // Tanggal harus hari ini atau masa depan (tidak boleh memesan di masa lalu)
            'tanggal_booking' => ['required', 'date', 'after_or_equal:today'],

            // Jam hanya wajib untuk tipe bukan paket (nullable + required_unless)
            'jam_mulai'       => ['nullable', 'date_format:H:i', 'required_unless:tipe_tarif,paket'],
            'jam_selesai'     => ['nullable', 'date_format:H:i', 'after:jam_mulai', 'required_unless:tipe_tarif,paket'],

            // Paket hanya wajib untuk tipe paket (nullable + required_if)
            'id_paket'        => ['nullable', 'exists:paket_harga,id_paket', 'required_if:tipe_tarif,paket'],
            'estimasi_hari'   => ['nullable', 'integer', 'min:1', 'max:365', 'required_if:tipe_tarif,paket'],

            'catatan_tambahan'=> ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Pesan error kustom yang lebih informatif daripada pesan default Laravel.
     */
    public function messages(): array
    {
        return [
            'jam_selesai.after'              => 'Jam selesai harus lebih besar dari jam mulai.',
            'tanggal_booking.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'jam_mulai.required_unless'      => 'Jam mulai wajib dipilih.',
            'jam_selesai.required_unless'    => 'Jam selesai wajib dipilih.',
            'id_paket.required_if'           => 'Pilih paket yang diinginkan.',
            'estimasi_hari.required_if'      => 'Estimasi hari pengerjaan wajib diisi.',
        ];
    }

    /**
     * Validasi tambahan setelah aturan standar (custom business rule).
     *
     * Masalah: 'nullable' + 'required_if' tidak bisa membedakan antara
     * string kosong "" dan null — keduanya lolos validasi.
     * Ini terjadi karena x-show di Alpine.js tetap mengirim field paket
     * meski tersembunyi.
     *
     * Solusi: validasi manual di after() hook — jika tipe_tarif = paket
     * tapi id_paket kosong string, tambahkan error secara eksplisit.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            // Fix: pastikan paket dipilih jika tipe tarif = paket
            if ($this->input('tipe_tarif') === 'paket' && empty($this->input('id_paket'))) {
                $v->errors()->add('id_paket', 'Pilih paket yang diinginkan.');
            }

            // Fix: batasi durasi booking maksimal 12 jam untuk tipe berbasis waktu
            if ($this->input('tipe_tarif') !== 'paket'
                && $this->input('jam_mulai')
                && $this->input('jam_selesai')
            ) {
                $mulai   = \Carbon\Carbon::createFromFormat('H:i', $this->input('jam_mulai'));
                $selesai = \Carbon\Carbon::createFromFormat('H:i', $this->input('jam_selesai'));

                if ($selesai->lte($mulai)) {
                    $v->errors()->add('jam_selesai', 'Jam selesai harus lebih besar dari jam mulai.');
                } elseif (abs($selesai->diffInHours($mulai)) > 12) {
                    $v->errors()->add('jam_selesai', 'Durasi booking tidak boleh lebih dari 12 jam.');
                }
            }
        });
    }
}
