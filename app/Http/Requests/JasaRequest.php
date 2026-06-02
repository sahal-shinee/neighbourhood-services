<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest JasaRequest
 *
 * Memvalidasi data yang dikirim dari form buat/edit jasa penyedia.
 *
 * Tantangan teknis:
 *  - Form mendukung tipe tarif: per_jam, per_pengerjaan, ATAU paket
 *  - Untuk tipe 'paket': ada array input dinamis (paket[0][nama], paket[0][harga], dll)
 *  - Alpine.js menggunakan x-show untuk menampilkan/menyembunyikan field,
 *    tapi field yang tersembunyi tetap dikirim dalam request
 *  - Solusi: prepareForValidation() membersihkan data tidak relevan SEBELUM validasi
 *
 * Otorisasi: hanya penyedia yang sudah diverifikasi yang bisa mengelola jasa.
 */
class JasaRequest extends FormRequest
{
    /**
     * Hanya penyedia terverifikasi yang boleh membuat/mengedit jasa.
     * Mencegah penyedia yang masih pending/ditolak menambahkan jasa.
     */
    public function authorize(): bool
    {
        return $this->user()->isPenyedia() && $this->user()->sudahDiverifikasi();
    }

    /**
     * Aturan validasi form jasa.
     *
     * Catatan untuk field paket (array):
     *  - paket[*].nama_paket dan paket[*].harga menggunakan wildcard '*'
     *    untuk memvalidasi setiap baris paket secara individual
     *  - Maksimal 5 paket per jasa
     */
    public function rules(): array
    {
        return [
            'nama_jasa'      => ['required', 'string', 'max:150'],
            'kategori_jasa'  => ['required', 'string'],
            // Deskripsi minimal 20 karakter agar tidak terlalu singkat
            'deskripsi_jasa' => ['required', 'string', 'min:20'],
            'tipe_tarif'     => ['required', 'in:per_jam,per_pengerjaan,paket'],
            // tarif_per_jam wajib KECUALI jika tipe = paket
            'tarif_per_jam'  => ['nullable', 'numeric', 'min:1000', 'required_unless:tipe_tarif,paket'],
            'foto_jasa'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_aktif'       => ['nullable', 'boolean'],

            // Array paket: nullable karena x-show tetap mengirim field meski tersembunyi
            'paket'              => ['nullable', 'array', 'max:5', 'required_if:tipe_tarif,paket'],
            'paket.*.nama_paket' => ['nullable', 'required_if:tipe_tarif,paket', 'string', 'max:100'],
            'paket.*.harga'      => ['nullable', 'required_if:tipe_tarif,paket', 'numeric', 'min:1000'],
            'paket.*.deskripsi'  => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Pesan error kustom untuk validasi kondisional yang kompleks.
     */
    public function messages(): array
    {
        return [
            'tarif_per_jam.required_unless' => 'Tarif wajib diisi untuk tipe Per Jam atau Per Pengerjaan.',
            'paket.required_if'             => 'Tambahkan minimal 1 paket harga.',
            'paket.*.harga.required'        => 'Harga paket wajib diisi.',
            'paket.*.nama_paket.required'   => 'Nama paket wajib diisi.',
        ];
    }

    /**
     * Bersihkan dan normalisasi data SEBELUM validasi dijalankan.
     *
     * Masalah yang diselesaikan:
     *  1. Checkbox is_aktif tidak terkirim jika tidak dicentang —
     *     merge() memastikan nilainya selalu ada (true/false)
     *
     *  2. Jika tipe = paket: hapus tarif_per_jam (tidak relevan)
     *
     *  3. Jika tipe bukan paket: hapus data paket[] (x-show menyembunyikan tapi tetap kirim)
     *     Ini mencegah validasi paket.*.nama_paket dijalankan untuk tipe non-paket
     */
    protected function prepareForValidation(): void
    {
        // Normalisasi checkbox is_aktif
        $this->merge([
            'is_aktif' => $this->boolean('is_aktif'),
        ]);

        if ($this->input('tipe_tarif') === 'paket') {
            // Tipe paket tidak menggunakan tarif_per_jam
            $this->merge(['tarif_per_jam' => null]);
        } else {
            // Tipe bukan paket: buang seluruh array paket agar tidak divalidasi
            $this->merge(['paket' => null]);
        }
    }

    /**
     * Validasi custom: untuk tipe paket, minimal SATU baris paket harus diisi penuh.
     *
     * Masalah: 'nullable' + 'required_if' tidak membedakan empty string dari null.
     * Baris paket yang kosong (nama/harga belum diisi) lolos validasi 'nullable'.
     * Solusi: cek manual di after() hook apakah ada setidaknya satu paket valid.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if ($this->input('tipe_tarif') !== 'paket') {
                return; // Tidak perlu cek paket untuk tipe lain
            }

            // Cek apakah ada minimal satu paket dengan nama DAN harga terisi
            $hasValid = collect($this->input('paket', []))
                ->contains(fn($p) => filled($p['nama_paket'] ?? null) && filled($p['harga'] ?? null));

            if (! $hasValid) {
                $v->errors()->add('paket', 'Tambahkan minimal 1 paket harga dengan nama dan harga terisi.');
            }
        });
    }
}
