<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Kategori
 *
 * Merepresentasikan kategori jasa yang tersedia di platform
 * (misal: Kebersihan Rumah, Perawatan Taman, Reparasi Elektronik, dll).
 *
 * Kategori dikelola oleh admin melalui panel admin.
 * Digunakan sebagai filter pencarian jasa oleh pelanggan.
 *
 * Kolom 'ikon_kategori' menyimpan emoji atau karakter ikon yang ditampilkan
 * di samping nama kategori.
 */
class Kategori extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kategori';

    // Primary key khusus
    protected $primaryKey = 'id_kategori';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'nama_kategori',  // Nama kategori, misal: "Kebersihan Rumah"
        'ikon_kategori',  // Emoji/ikon, misal: "🏠", "🌿", "⚡"
    ];
}
