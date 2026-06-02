<?php

namespace Database\Seeders;

use App\Models\Jasa;
use App\Models\Kategori;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class JasaSeeder extends Seeder
{
    public function run(): void
    {
        $penyedia = Pengguna::penyedia()->get();
        $kategori = Kategori::all();

        if ($penyedia->isEmpty() || $kategori->isEmpty()) {
            return;
        }

        $jasaData = [
            [
                'nama' => 'Jasa Bersih-bersih Rumah Menyeluruh',
                'kategori' => 'Kebersihan Rumah',
                'deskripsi' => 'Melayani pembersihan seluruh area rumah, termasuk menyapu, mengepel, membersihkan kamar mandi, dan merapikan barang-barang. Dijamin bersih dan wangi.',
                'tarif' => 50000,
            ],
            [
                'nama' => 'Service AC Bergaransi',
                'kategori' => 'Perbaikan Elektronik',
                'deskripsi' => 'Cuci AC, tambah freon, perbaikan AC bocor atau tidak dingin. Bergaransi 1 bulan untuk setiap perbaikan.',
                'tarif' => 75000,
            ],
            [
                'nama' => 'Tukang Cat Dinding Interior/Eksterior',
                'kategori' => 'Tukang Bangunan',
                'deskripsi' => 'Menerima jasa pengecatan dinding rumah, kantor, atau apartemen. Hasil rapi, pengerjaan cepat, dan berpengalaman.',
                'tarif' => 60000,
            ],
            [
                'nama' => 'Jasa Potong Rumput & Rapi Taman',
                'kategori' => 'Perawatan Taman',
                'deskripsi' => 'Memotong rumput liar, merapikan tanaman hias, dan membersihkan area taman agar kembali indah dan asri.',
                'tarif' => 45000,
            ],
            [
                'nama' => 'Guru Les Privat Matematika SD/SMP',
                'kategori' => 'Les Privat',
                'deskripsi' => 'Mengajar Matematika untuk tingkat SD dan SMP. Metode belajar menyenangkan dan mudah dipahami oleh anak.',
                'tarif' => 80000,
            ],
            [
                'nama' => 'Jasa Setrika Pakaian Kilat',
                'kategori' => 'Laundry & Setrika',
                'deskripsi' => 'Melayani jasa setrika pakaian harian atau mingguan. Pakaian dijamin rapi, wangi, dan siap pakai.',
                'tarif' => 30000,
            ],
            [
                'nama' => 'Jasa Masak Harian untuk Keluarga',
                'kategori' => 'Jasa Memasak',
                'deskripsi' => 'Menyediakan jasa memasak masakan rumahan yang lezat dan bergizi untuk keluarga. Menu bisa disesuaikan dengan selera.',
                'tarif' => 55000,
            ],
            [
                'nama' => 'Cuci Mobil & Motor Panggilan',
                'kategori' => 'Perawatan Kendaraan',
                'deskripsi' => 'Jasa cuci kendaraan roda dua dan roda empat langsung di rumah Anda. Bersih mengkilap luar dalam.',
                'tarif' => 40000,
            ],
        ];

        foreach ($penyedia as $p) {
            // Give each penyedia 2 random services
            $selectedJasa = collect($jasaData)->random(2);

            foreach ($selectedJasa as $data) {
                Jasa::create([
                    'id_penyedia' => $p->id_pengguna,
                    'nama_jasa' => $data['nama'],
                    'kategori_jasa' => $data['kategori'],
                    'deskripsi_jasa' => $data['deskripsi'],
                    'tarif_per_jam' => $data['tarif'],
                    'is_aktif' => true,
                ]);
            }
        }
    }
}
