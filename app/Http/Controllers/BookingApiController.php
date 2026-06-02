<?php

namespace App\Http\Controllers;

use App\Models\PesananJasa;
use Illuminate\Http\Request;

/**
 * Controller BookingApiController
 *
 * Menyediakan endpoint API internal (bukan REST API publik) yang dikonsumsi
 * oleh JavaScript di halaman form booking.
 *
 * Digunakan untuk mengambil jadwal penyedia yang sudah terpesan
 * sehingga form booking bisa memblokir slot waktu yang tidak tersedia
 * secara dinamis (tanpa reload halaman).
 */
class BookingApiController extends Controller
{
    /**
     * Kembalikan daftar slot waktu yang sudah terpesan untuk penyedia tertentu.
     *
     * Hanya mengembalikan pesanan yang:
     *  - Sudah 'disetujui' (bukan sekadar menunggu)
     *  - Tanggal booking >= hari ini (tidak perlu data historis)
     *
     * Response JSON digunakan oleh JavaScript di form booking untuk:
     *  - Menonaktifkan tombol "Pesan" pada slot yang sudah terisi
     *  - Menampilkan peringatan tumpang tindih jadwal secara real-time
     *
     * Rute: GET /api/jadwal-terpesan/{penyedia_id}
     *
     * @param  int $penyedia_id  ID pengguna penyedia
     * @return \Illuminate\Http\JsonResponse
     */
    public function jadwalTerpesan($penyedia_id)
    {
        // Join ke tabel jasa untuk mendapatkan pesanan berdasarkan id_penyedia
        $pesanan = PesananJasa::join('jasa', 'pesanan_jasa.id_jasa', '=', 'jasa.id_jasa')
            ->where('jasa.id_penyedia', $penyedia_id)
            ->where('pesanan_jasa.status_pesanan', 'disetujui')
            ->where('pesanan_jasa.tanggal_booking', '>=', now()->toDateString()) // Hanya jadwal mendatang
            ->get(['pesanan_jasa.tanggal_booking', 'pesanan_jasa.jam_mulai', 'pesanan_jasa.jam_selesai']);

        // Kembalikan sebagai JSON array untuk dikonsumsi JavaScript
        return response()->json($pesanan);
    }
}
