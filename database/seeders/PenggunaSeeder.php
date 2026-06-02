<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Pengguna::create([
            'nama_lengkap'       => 'Administrator Sistem',
            'email'              => 'admin@neighbourhood.test',
            'password'           => Hash::make('password'),
            'peran'              => 'admin',
            'status_verifikasi'  => 'diverifikasi',
            'no_telepon'         => '081200000000',
            'alamat'             => 'Jakarta Pusat',
        ]);

        // Penyedia Jasa
        $penyedias = [
            [
                'nama_lengkap' => 'Budi Santoso',
                'email'        => 'budi@test.com',
                'no_telepon'   => '081211111111',
                'alamat'       => 'Jl. Sudirman No.1, Jakarta Selatan',
                'latitude'     => -6.2088,
                'longitude'    => 106.8456,
            ],
            [
                'nama_lengkap' => 'Siti Rahayu',
                'email'        => 'siti@test.com',
                'no_telepon'   => '081222222222',
                'alamat'       => 'Jl. Thamrin No.5, Jakarta Pusat',
                'latitude'     => -6.1751,
                'longitude'    => 106.8272,
            ],
            [
                'nama_lengkap' => 'Ahmad Fauzi',
                'email'        => 'ahmad@test.com',
                'no_telepon'   => '081233333333',
                'alamat'       => 'Jl. Kemang Raya No.10, Jakarta Selatan',
                'latitude'     => -6.2297,
                'longitude'    => 106.8294,
            ],
            [
                'nama_lengkap' => 'Dewi Lestari',
                'email'        => 'dewi@test.com',
                'no_telepon'   => '081244444444',
                'alamat'       => 'Jl. Fatmawati No.20, Jakarta Selatan',
                'latitude'     => -6.1944,
                'longitude'    => 106.8229,
            ],
            [
                'nama_lengkap' => 'Rudi Hermawan',
                'email'        => 'rudi@test.com',
                'no_telepon'   => '081255555555',
                'alamat'       => 'Jl. Gatot Subroto No.8, Jakarta Selatan',
                'latitude'     => -6.2146,
                'longitude'    => 106.8451,
            ],
        ];

        foreach ($penyedias as $data) {
            Pengguna::create(array_merge($data, [
                'password'          => Hash::make('password'),
                'peran'             => 'penyedia',
                'status_verifikasi' => 'diverifikasi',
            ]));
        }

        // Pelanggan
        $pelanggan = [
            [
                'nama_lengkap' => 'Andi Wijaya',
                'email'        => 'andi@test.com',
                'no_telepon'   => '081266666666',
                'alamat'       => 'Jl. Kebon Jeruk No.3, Jakarta Barat',
                'latitude'     => -6.2000,
                'longitude'    => 106.8166,
            ],
            [
                'nama_lengkap' => 'Maya Putri',
                'email'        => 'maya@test.com',
                'no_telepon'   => '081277777777',
                'alamat'       => 'Jl. Casablanca No.15, Jakarta Selatan',
                'latitude'     => -6.1868,
                'longitude'    => 106.8341,
            ],
            [
                'nama_lengkap' => 'Tono Susanto',
                'email'        => 'tono@test.com',
                'no_telepon'   => '081288888888',
                'alamat'       => 'Jl. Puri Indah No.7, Jakarta Barat',
                'latitude'     => -6.2189,
                'longitude'    => 106.8166,
            ],
            [
                'nama_lengkap' => 'Rina Kusuma',
                'email'        => 'rina@test.com',
                'no_telepon'   => '081299999999',
                'alamat'       => 'Jl. Hayam Wuruk No.22, Jakarta Pusat',
                'latitude'     => -6.1700,
                'longitude'    => 106.8272,
            ],
            [
                'nama_lengkap' => 'Hendra Gunawan',
                'email'        => 'hendra@test.com',
                'no_telepon'   => '081200001111',
                'alamat'       => 'Jl. Tebet Barat No.9, Jakarta Selatan',
                'latitude'     => -6.2031,
                'longitude'    => 106.8557,
            ],
        ];

        foreach ($pelanggan as $data) {
            Pengguna::create(array_merge($data, [
                'password'          => Hash::make('password'),
                'peran'             => 'pelanggan',
                'status_verifikasi' => 'diverifikasi',
            ]));
        }
    }
}
