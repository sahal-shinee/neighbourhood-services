<?php

namespace Database\Seeders;

use App\Models\PesananJasa;
use App\Models\UlasanJasa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UlasanSeeder extends Seeder
{
    public function run(): void
    {
        // Only get completed bookings
        $pesananSelesai = PesananJasa::selesai()->get();

        if ($pesananSelesai->isEmpty()) {
            return;
        }

        $komentar = [
            'Sangat memuaskan, pekerjaannya cepat dan rapi.',
            'Penyedia jasa sangat ramah dan profesional. Mantap!',
            'Hasilnya lumayan bagus, sesuai dengan harga yang dibayarkan.',
            'Bagus, tapi datang agak terlambat 15 menit.',
            'Luar biasa! Sangat merekomendasikan jasa ini.',
            'Pekerjaan selesai tepat waktu, terima kasih.',
            'Cukup puas dengan pelayanannya.',
        ];

        foreach ($pesananSelesai as $pesanan) {
            // Not all completed bookings have reviews (e.g., 70% chance)
            if (rand(1, 10) <= 7) {
                // Bias towards higher ratings
                $rating = rand(1, 100) <= 80 ? rand(4, 5) : rand(3, 4); 
                
                UlasanJasa::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'rating' => $rating,
                    'komentar_ulasan' => $komentar[array_rand($komentar)],
                    'tanggal_ulasan' => Carbon::parse($pesanan->tanggal_booking)->addDays(rand(1, 3))->format('Y-m-d'),
                ]);
            }
        }
    }
}
