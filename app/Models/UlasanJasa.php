<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model UlasanJasa
 *
 * Merepresentasikan ulasan/review yang diberikan pelanggan setelah pesanan selesai.
 * Setiap pesanan hanya bisa diulas satu kali (relasi hasOne dari PesananJasa).
 *
 * Rating menggunakan skala 1–5 bintang.
 * Ulasan ini berkontribusi pada perhitungan rating_rata_rata penyedia.
 */
class UlasanJasa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'ulasan_jasa';

    // Primary key khusus
    protected $primaryKey = 'id_ulasan';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_pesanan',       // Pesanan yang diulas
        'rating',           // Nilai 1–5 bintang
        'komentar_ulasan',  // Teks ulasan opsional dari pelanggan
        'tanggal_ulasan',   // Tanggal ulasan diberikan
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'tanggal_ulasan' => 'date',    // Otomatis jadi objek Carbon
        'rating'         => 'integer', // Pastikan bertipe integer
    ];

    // =====================================================================
    // Relationships — Relasi ke model lain
    // =====================================================================

    /**
     * Pesanan yang terkait dengan ulasan ini.
     */
    public function pesanan()
    {
        return $this->belongsTo(PesananJasa::class, 'id_pesanan', 'id_pesanan');
    }

    /**
     * Pelanggan yang memberikan ulasan ini.
     *
     * Menggunakan hasOneThrough karena:
     *   UlasanJasa → (melalui) PesananJasa → Pengguna (pelanggan)
     *
     * Alur relasi:
     *  UlasanJasa.id_pesanan → PesananJasa.id_pesanan
     *  PesananJasa.id_pelanggan → Pengguna.id_pengguna
     */
    public function pelanggan()
    {
        return $this->hasOneThrough(
            Pengguna::class,         // Model tujuan akhir
            PesananJasa::class,      // Model perantara
            'id_pesanan',            // FK di PesananJasa yang menunjuk ke UlasanJasa
            'id_pengguna',           // FK di Pengguna
            'id_pesanan',            // Local key di UlasanJasa
            'id_pelanggan'           // Local key di PesananJasa yang menunjuk ke Pengguna
        );
    }
}
