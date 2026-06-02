<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model PortofolioJasa
 *
 * Merepresentasikan satu item portofolio/karya yang ditampilkan penyedia jasa
 * sebagai bukti pengalaman kerja mereka.
 *
 * Ditampilkan di halaman profil publik penyedia untuk membangun kepercayaan pelanggan.
 */
class PortofolioJasa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'portofolio_jasa';

    // Primary key khusus
    protected $primaryKey = 'id_portofolio';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_penyedia',       // Penyedia pemilik portofolio ini
        'judul_proyek',      // Judul singkat karya, misal: "Renovasi Taman Minimalis"
        'deskripsi_proyek',  // Penjelasan detail tentang proyek yang dikerjakan
        'foto_proyek',       // Path file foto hasil karya (disimpan di storage/public)
    ];

    // =====================================================================
    // Relationships
    // =====================================================================

    /**
     * Penyedia yang memiliki portofolio ini.
     */
    public function penyedia()
    {
        return $this->belongsTo(Pengguna::class, 'id_penyedia', 'id_pengguna');
    }

    // =====================================================================
    // Accessors — Atribut virtual untuk URL foto
    // =====================================================================

    /**
     * URL foto proyek dengan fallback placeholder jika foto belum diunggah.
     *
     * Prioritas:
     *  1. Foto yang diunggah penyedia (dari storage/public)
     *  2. Placeholder dari placehold.co dengan judul proyek sebagai teks
     *
     * Diakses via: $portofolio->foto_proyek_url
     */
    public function getFotoProyekUrlAttribute(): string
    {
        // Cek apakah foto ada di storage
        if ($this->foto_proyek && Storage::disk('public')->exists($this->foto_proyek)) {
            return Storage::disk('public')->url($this->foto_proyek);
        }

        // Fallback: placeholder bergambar dengan judul proyek
        return 'https://placehold.co/400x300/6366f1/ffffff?text='.urlencode($this->judul_proyek ?? 'Portfolio');
    }
}
