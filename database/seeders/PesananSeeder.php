<?php

namespace Database\Seeders;

use App\Models\Jasa;
use App\Models\Pengguna;
use App\Models\PesananJasa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = Pengguna::pelanggan()->get();
        $jasa = Jasa::all();

        if ($pelanggan->isEmpty() || $jasa->isEmpty()) {
            return;
        }

        $statuses = ['menunggu', 'disetujui', 'selesai', 'dibatalkan'];

        foreach ($pelanggan as $p) {
            // Each pelanggan makes 3-5 random bookings
            $numBookings = rand(3, 5);

            for ($i = 0; $i < $numBookings; $i++) {
                $selectedJasa = $jasa->random();
                
                // Random date from last 30 days to next 7 days
                $daysOffset = rand(-30, 7);
                $tanggal = Carbon::now()->addDays($daysOffset)->format('Y-m-d');
                
                // Random time
                $jamMulai = rand(8, 15);
                $durasi = rand(1, 4);
                $jamSelesai = $jamMulai + $durasi;

                $status = $statuses[array_rand($statuses)];
                
                // Logic for status based on date
                if ($daysOffset < 0 && $status == 'menunggu') {
                    $status = 'selesai'; // past bookings shouldn't stay 'menunggu' realistically in seeder
                }

                PesananJasa::create([
                    'id_pelanggan' => $p->id_pengguna,
                    'id_jasa' => $selectedJasa->id_jasa,
                    'tanggal_booking' => $tanggal,
                    'jam_mulai' => sprintf('%02d:00:00', $jamMulai),
                    'jam_selesai' => sprintf('%02d:00:00', $jamSelesai),
                    'status_pesanan' => $status,
                    'catatan_tambahan' => 'Mohon datang tepat waktu ya.',
                ]);
            }
        }
    }
}
