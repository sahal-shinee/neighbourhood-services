<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PesananJasa
 *
 * Merepresentasikan satu transaksi pesanan antara pelanggan dan penyedia jasa.
 * Ini adalah inti dari alur bisnis marketplace:
 *   Pelanggan memesan → Penyedia menyetujui/menolak → Penyedia menyelesaikan → Pelanggan mengulas
 *
 * Alur status pesanan (state machine):
 *   'menunggu' → 'disetujui' → 'selesai'
 *   'menunggu' → 'dibatalkan'  (oleh pelanggan atau penyedia)
 *   'disetujui' → 'dibatalkan' (oleh penyedia)
 */
class PesananJasa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pesanan_jasa';

    // Primary key khusus
    protected $primaryKey = 'id_pesanan';

    /**
     * Kolom yang boleh diisi secara massal.
     * Catatan: id_paket dan estimasi_hari hanya relevan untuk tipe tarif 'paket'.
     *          jam_mulai dan jam_selesai hanya relevan untuk tipe 'per_jam'/'per_pengerjaan'.
     */
    protected $fillable = [
        'id_pelanggan',
        'id_jasa',
        'id_paket',          // Paket yang dipilih (nullable, hanya untuk tipe paket)
        'estimasi_hari',     // Estimasi durasi pengerjaan dalam hari (nullable, hanya paket)
        'tanggal_booking',
        'jam_mulai',         // Nullable — tidak digunakan untuk tipe paket
        'jam_selesai',       // Nullable — tidak digunakan untuk tipe paket
        'status_pesanan',    // Enum: menunggu|disetujui|selesai|dibatalkan
        'catatan_tambahan',  // Instruksi khusus dari pelanggan ke penyedia
    ];

    /**
     * Casting kolom tanggal agar otomatis menjadi objek Carbon.
     */
    protected $casts = [
        'tanggal_booking' => 'date',
    ];

    // =====================================================================
    // Relationships — Relasi ke model lain
    // =====================================================================

    /**
     * Pelanggan yang membuat pesanan ini.
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pengguna::class, 'id_pelanggan', 'id_pengguna');
    }

    /**
     * Jasa/layanan yang dipesan.
     */
    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'id_jasa', 'id_jasa');
    }

    /**
     * Paket harga spesifik yang dipilih pelanggan (hanya untuk tipe tarif 'paket').
     */
    public function paket()
    {
        return $this->belongsTo(PaketHarga::class, 'id_paket', 'id_paket');
    }

    /**
     * Ulasan yang diberikan pelanggan setelah pesanan selesai.
     * Relasi hasOne karena setiap pesanan hanya boleh diulas satu kali.
     */
    public function ulasan()
    {
        return $this->hasOne(UlasanJasa::class, 'id_pesanan', 'id_pesanan');
    }

    // =====================================================================
    // Query Scopes — Filter berdasarkan status pesanan
    // =====================================================================

    /** Scope: pesanan yang belum ditanggapi penyedia. */
    public function scopeMenunggu($query)
    {
        return $query->where('status_pesanan', 'menunggu');
    }

    /** Scope: pesanan yang sudah disetujui dan sedang berjalan. */
    public function scopeDisetujui($query)
    {
        return $query->where('status_pesanan', 'disetujui');
    }

    /** Scope: pesanan yang sudah selesai dikerjakan. */
    public function scopeSelesai($query)
    {
        return $query->where('status_pesanan', 'selesai');
    }

    /** Scope: pesanan yang dibatalkan oleh pelanggan atau ditolak penyedia. */
    public function scopeDibatalkan($query)
    {
        return $query->where('status_pesanan', 'dibatalkan');
    }

    // =====================================================================
    // Helpers — Atribut virtual untuk tampilan UI
    // =====================================================================

    /**
     * Label status pesanan dalam Bahasa Indonesia untuk ditampilkan di UI.
     * Diakses via: $pesanan->status_label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_pesanan) {
            'menunggu'   => 'Menunggu',
            'disetujui'  => 'Disetujui',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default      => ucfirst($this->status_pesanan),
        };
    }

    /**
     * Varian warna status untuk komponen badge di tampilan UI.
     * Nilai dikonsumsi oleh komponen Blade/Tailwind untuk menentukan warna chip.
     *
     * Contoh nilai: 'warning' (kuning), 'info' (biru), 'success' (hijau), 'danger' (merah)
     *
     * Diakses via: $pesanan->status_variant
     */
    public function getStatusVariantAttribute(): string
    {
        return match ($this->status_pesanan) {
            'menunggu'   => 'warning',   // Kuning — menunggu aksi
            'disetujui'  => 'info',      // Biru — sedang berjalan
            'selesai'    => 'success',   // Hijau — berhasil selesai
            'dibatalkan' => 'danger',    // Merah — gagal/dibatalkan
            default      => 'neutral',
        };
    }
}
