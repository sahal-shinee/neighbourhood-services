<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Jasa;
use App\Models\PesananJasa;
use App\Notifications\PesananBaruNotification;
use Illuminate\Http\Request;

/**
 * Controller PelangganBookingController
 *
 * Mengelola proses pembuatan pesanan (booking) oleh pelanggan.
 *
 * Dua tipe booking yang ditangani:
 *  1. Berbasis waktu (per_jam/per_pengerjaan): butuh jam_mulai dan jam_selesai
 *     → Dilengkapi cek overlap jadwal untuk mencegah double booking
 *  2. Berbasis paket: butuh id_paket dan estimasi_hari
 *     → Tidak ada cek overlap karena tidak terikat pada jam tertentu
 */
class PelangganBookingController extends Controller
{
    /**
     * Tampilkan form pembuatan pesanan untuk jasa tertentu.
     * Mengirimkan data jasa, penyedia, dan paket harga ke view.
     */
    public function create($jasa_id)
    {
        // Load relasi penyedia dan paket untuk ditampilkan di form booking
        $jasa = Jasa::with('penyedia', 'paket')->findOrFail($jasa_id);
        return view('pelanggan.booking.create', compact('jasa'));
    }

    /**
     * Simpan pesanan baru ke database.
     *
     * Alur validasi dan penyimpanan:
     *  1. Validasi data via BookingRequest (tanggal, jam, paket, dll)
     *  2. Untuk tipe berbasis waktu: cek overlap jadwal dengan pesanan lain
     *  3. Susun data pesanan sesuai tipe tarif
     *  4. Simpan pesanan, kirim notifikasi ke penyedia
     */
    public function store(BookingRequest $request)
    {
        $data = $request->validated();
        $jasa = Jasa::findOrFail($data['jasa_id']);

        // Pengecekan tumpang tindih jadwal HANYA untuk tipe tarif berbasis waktu.
        // Tipe 'paket' tidak memiliki jam tertentu, jadi tidak perlu cek overlap.
        if ($jasa->tipe_tarif !== 'paket') {
            // Query join untuk mendapatkan pesanan disetujui penyedia yang sama
            // pada tanggal yang sama dengan waktu yang tumpang tindih.
            $overlap = PesananJasa::join('jasa', 'pesanan_jasa.id_jasa', '=', 'jasa.id_jasa')
                ->where('jasa.id_penyedia', $jasa->id_penyedia)
                ->where('pesanan_jasa.tanggal_booking', $data['tanggal_booking'])
                ->where('pesanan_jasa.status_pesanan', 'disetujui')
                ->where(function ($query) use ($data) {
                    // Kondisi tumpang tindih: jam baru mulai sebelum yang lama berakhir
                    // DAN jam baru belum selesai saat yang lama mulai
                    $query->where('jam_mulai', '<', $data['jam_selesai'])
                          ->where('jam_selesai', '>', $data['jam_mulai']);
                })->count();

            // Tolak booking jika ada jadwal yang bertabrakan
            if ($overlap > 0) {
                return back()->withErrors([
                    'overlap' => 'Penyedia jasa sudah memiliki jadwal yang bertabrakan pada waktu tersebut. Silakan pilih waktu lain.',
                ])->withInput();
            }
        }

        // Susun data dasar pesanan yang berlaku untuk semua tipe tarif
        $pesananData = [
            'id_pelanggan'     => $request->user()->id_pengguna,
            'id_jasa'          => $jasa->id_jasa,
            'tanggal_booking'  => $data['tanggal_booking'],
            'status_pesanan'   => 'menunggu', // Status awal: menunggu persetujuan penyedia
            'catatan_tambahan' => $data['catatan_tambahan'] ?? null,
        ];

        // Tambahkan field spesifik berdasarkan tipe tarif jasa
        if ($jasa->tipe_tarif === 'paket') {
            // Untuk paket: simpan id_paket dan estimasi hari, jam dikosongkan
            $pesananData['id_paket']      = $data['id_paket'];
            $pesananData['estimasi_hari'] = $data['estimasi_hari'];
            $pesananData['jam_mulai']     = null;
            $pesananData['jam_selesai']   = null;
        } else {
            // Untuk per_jam/per_pengerjaan: simpan jam, paket dikosongkan
            $pesananData['jam_mulai']   = $data['jam_mulai'];
            $pesananData['jam_selesai'] = $data['jam_selesai'];
        }

        // Simpan pesanan ke database
        $pesanan = PesananJasa::create($pesananData);

        // Kirim notifikasi real-time ke penyedia bahwa ada pesanan baru masuk
        $jasa->penyedia->notify(new PesananBaruNotification($pesanan->load('pelanggan', 'jasa')));

        return redirect()->route('pelanggan.pesanan.index')
            ->with('success', 'Booking berhasil dikirim. Menunggu persetujuan penyedia.');
    }
}
