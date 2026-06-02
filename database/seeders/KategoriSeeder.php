<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Kebersihan Rumah',      'ikon_kategori' => '🧹'],
            ['nama_kategori' => 'Perbaikan Elektronik',  'ikon_kategori' => '🔧'],
            ['nama_kategori' => 'Tukang Bangunan',       'ikon_kategori' => '🏗️'],
            ['nama_kategori' => 'Perawatan Taman',       'ikon_kategori' => '🌿'],
            ['nama_kategori' => 'Les Privat',            'ikon_kategori' => '📚'],
            ['nama_kategori' => 'Laundry & Setrika',     'ikon_kategori' => '👕'],
            ['nama_kategori' => 'Jasa Memasak',          'ikon_kategori' => '🍳'],
            ['nama_kategori' => 'Perawatan Kendaraan',   'ikon_kategori' => '🚗'],
        ];

        foreach ($kategoris as $k) {
            Kategori::create($k);
        }
    }
}
