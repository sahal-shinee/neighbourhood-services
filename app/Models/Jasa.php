<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model Jasa
 *
 * Merepresentasikan satu layanan/jasa yang ditawarkan oleh penyedia.
 * Satu penyedia bisa memiliki banyak jasa (relasi hasMany dari Pengguna).
 *
 * Sistem tarif mendukung tiga tipe:
 *  - per_jam        : dibayar per jam kerja (butuh jam_mulai & jam_selesai)
 *  - per_pengerjaan : tarif tetap per satu pekerjaan
 *  - paket          : memiliki beberapa tingkatan harga (relasi ke PaketHarga)
 */
class Jasa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'jasa';

    // Primary key khusus
    protected $primaryKey = 'id_jasa';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_penyedia',
        'nama_jasa',
        'kategori_jasa',
        'deskripsi_jasa',
        'tipe_tarif',
        'tarif_per_jam',   // Digunakan untuk tipe per_jam dan per_pengerjaan
        'foto_jasa',
        'is_aktif',        // Penyedia bisa menonaktifkan jasa tanpa menghapusnya
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'tarif_per_jam' => 'decimal:2', // Format uang dengan 2 angka desimal
        'is_aktif'      => 'boolean',
    ];

    // =====================================================================
    // Relationships — Relasi ke model lain
    // =====================================================================

    /**
     * Penyedia jasa yang memiliki layanan ini.
     * Relasi ke model Pengguna berperan 'penyedia'.
     */
    public function penyedia()
    {
        return $this->belongsTo(Pengguna::class, 'id_penyedia', 'id_pengguna');
    }

    /**
     * Semua pesanan yang masuk untuk jasa ini.
     */
    public function pesanan()
    {
        return $this->hasMany(PesananJasa::class, 'id_jasa', 'id_jasa');
    }

    /**
     * Paket harga yang tersedia untuk jasa bertipe 'paket'.
     * Diurutkan berdasarkan kolom 'urutan' untuk tampilan konsisten (Paket 1, 2, 3...).
     */
    public function paket()
    {
        return $this->hasMany(PaketHarga::class, 'id_jasa', 'id_jasa')->orderBy('urutan');
    }

    // =====================================================================
    // Accessors — Atribut virtual untuk tampilan harga
    // =====================================================================

    /**
     * Label harga yang sudah diformat sesuai tipe tarif.
     * Digunakan di kartu layanan dan halaman detail.
     *
     * Contoh output:
     *  - per_jam        → "Rp 50.000/jam"
     *  - per_pengerjaan → "Rp 150.000/pengerjaan"
     *  - paket          → "Mulai Rp 200.000"
     *
     * Diakses via: $jasa->tarif_label
     */
    public function getTarifLabelAttribute(): string
    {
        return match ($this->tipe_tarif) {
            'per_jam'        => 'Rp ' . number_format((float)$this->tarif_per_jam, 0, ',', '.') . '/jam',
            'per_pengerjaan' => 'Rp ' . number_format((float)$this->tarif_per_jam, 0, ',', '.') . '/pengerjaan',
            // Untuk paket, tampilkan harga paket termurah
            'paket'          => 'Mulai Rp ' . number_format((float)($this->paket->min('harga') ?? 0), 0, ',', '.'),
            default          => '-',
        };
    }

    /**
     * Sufiks singkat untuk tampilan kompak di kartu layanan.
     * Diakses via: $jasa->tarif_suffix
     */
    public function getTarifSuffixAttribute(): string
    {
        return match ($this->tipe_tarif) {
            'per_jam'        => '/jam',
            'per_pengerjaan' => '/pengerjaan',
            'paket'          => '',
            default          => '',
        };
    }

    /**
     * URL foto layanan dengan fallback berdasarkan kategori.
     *
     * Prioritas:
     *  1. Foto yang diunggah penyedia (dari storage/public)
     *  2. Foto fallback dari Unsplash sesuai kategori jasa
     *  3. Foto generik jika kategori tidak dikenali
     *
     * Diakses via: $jasa->foto_jasa_url
     */
    public function getFotoJasaUrlAttribute(): string
    {
        // Gunakan foto yang diunggah jika tersedia
        if ($this->foto_jasa && Storage::disk('public')->exists($this->foto_jasa)) {
            return Storage::disk('public')->url($this->foto_jasa);
        }

        // Peta kategori ke foto Unsplash yang relevan
        $fallbackImages = [
            'Kebersihan Rumah'     => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?q=80&w=400&auto=format&fit=crop',
            'Perawatan Taman'      => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?q=80&w=400&auto=format&fit=crop',
            'Laundry & Setrika'    => 'https://images.unsplash.com/photo-1545173168-9f1947e80154?q=80&w=400&auto=format&fit=crop',
            'Jasa Memasak'         => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=400&auto=format&fit=crop',
            'Reparasi Elektronik'  => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=400&auto=format&fit=crop',
            'Pendidikan & Les'     => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=400&auto=format&fit=crop',
            'Perawatan Kendaraan'  => 'https://images.unsplash.com/photo-1607860108855-64acf2078ed9?q=80&w=400&auto=format&fit=crop',
            'Kesehatan & Kebugaran'=> 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=400&auto=format&fit=crop',
        ];

        // Kembalikan foto sesuai kategori, atau foto generik jika tidak ditemukan
        return $fallbackImages[$this->kategori_jasa] ?? 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?q=80&w=400&auto=format&fit=crop';
    }

    // =====================================================================
    // Scopes — Query shortcut
    // =====================================================================

    /**
     * Scope: hanya ambil jasa yang sedang aktif ditawarkan.
     * Contoh: Jasa::aktif()->get()
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
