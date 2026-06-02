<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model PaketHarga
 *
 * Merepresentasikan satu tingkatan harga dalam sistem tarif 'paket'.
 * Satu jasa bertipe 'paket' bisa memiliki hingga 5 paket harga (misalnya: Paket Basic, Standard, Premium).
 *
 * Kolom 'urutan' menentukan urutan tampilan paket di halaman detail jasa.
 */
class PaketHarga extends Model
{
    // Nama tabel di database
    protected $table = 'paket_harga';

    // Primary key khusus
    protected $primaryKey = 'id_paket';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_jasa',       // Jasa induk yang memiliki paket ini
        'nama_paket',    // Nama tingkatan, misal: "Basic", "Standard", "Premium"
        'harga',         // Harga paket dalam Rupiah
        'deskripsi',     // Penjelasan apa saja yang termasuk dalam paket ini
        'urutan',        // Angka urut untuk pengurutan tampilan (1, 2, 3...)
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'harga'  => 'decimal:2', // Format uang dengan 2 angka desimal
        'urutan' => 'integer',
    ];

    // =====================================================================
    // Relationships
    // =====================================================================

    /**
     * Jasa induk yang memiliki paket harga ini.
     */
    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'id_jasa', 'id_jasa');
    }
}
