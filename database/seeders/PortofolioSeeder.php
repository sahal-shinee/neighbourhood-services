<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\PortofolioJasa;
use Illuminate\Database\Seeder;

class PortofolioSeeder extends Seeder
{
    public function run(): void
    {
        $penyedia = Pengguna::penyedia()->get();

        if ($penyedia->isEmpty()) {
            return;
        }

        $portofolioData = [
            [
                'judul' => 'Hasil Kerja: Pembersihan Rumah Tipe 36',
                'deskripsi' => 'Pembersihan menyeluruh rumah setelah renovasi. Seluruh debu dan sisa material bangunan dibersihkan dengan sempurna.',
            ],
            [
                'judul' => 'Service AC Split Daikin 1 PK',
                'deskripsi' => 'Perbaikan AC yang kurang dingin karena kekurangan freon dan filter kotor. Kembali dingin seperti baru.',
            ],
            [
                'judul' => 'Pengecatan Ruang Tamu Minimalis',
                'deskripsi' => 'Mengecat ulang ruang tamu dengan warna pastel. Hasil rapi tanpa noda cat di lantai atau perabotan.',
            ],
            [
                'judul' => 'Penataan Taman Belakang',
                'deskripsi' => 'Merapikan rumput dan menanam beberapa bunga hias untuk mempercantik taman belakang rumah.',
            ],
            [
                'judul' => 'Siswa Juara 1 Olimpiade Matematika',
                'deskripsi' => 'Bimbingan intensif selama 3 bulan membuahkan hasil, siswa berhasil meraih juara 1 di tingkat kota.',
            ],
            [
                'judul' => 'Setrika 50 Kg Pakaian Keluarga',
                'deskripsi' => 'Menyelesaikan setrika tumpukan pakaian yang sangat banyak dalam waktu singkat. Rapi dan harum.',
            ],
            [
                'judul' => 'Catering Acara Syukuran Keluarga',
                'deskripsi' => 'Memasak berbagai hidangan tradisional untuk acara syukuran yang dihadiri oleh 50 tamu undangan.',
            ],
            [
                'judul' => 'Salon Mobil Eksterior Panggilan',
                'deskripsi' => 'Membersihkan kerak air dan memoles body mobil hingga mengkilap di garasi rumah pelanggan.',
            ],
        ];

        foreach ($penyedia as $p) {
            // Give each penyedia 3 random portfolios
            $selectedPorto = collect($portofolioData)->random(3);

            foreach ($selectedPorto as $data) {
                PortofolioJasa::create([
                    'id_penyedia' => $p->id_pengguna,
                    'judul_proyek' => $data['judul'],
                    'deskripsi_proyek' => $data['deskripsi'],
                    'foto_proyek' => 'portofolio/placeholder.jpg', // Dummy path, accessor handles it
                ]);
            }
        }
    }
}
