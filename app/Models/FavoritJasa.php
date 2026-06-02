<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model FavoritJasa
 *
 * Menyimpan daftar jasa yang difavoritkan/disimpan oleh pelanggan.
 * Setiap kombinasi id_pelanggan + id_jasa bersifat unik (satu pelanggan
 * hanya bisa favorit satu jasa satu kali).
 */
class FavoritJasa extends Model
{
    protected $table      = 'favorit_jasa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_pelanggan',
        'id_jasa',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pengguna::class, 'id_pelanggan', 'id_pengguna');
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'id_jasa', 'id_jasa');
    }
}
