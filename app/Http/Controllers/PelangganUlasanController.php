<?php

namespace App\Http\Controllers;

use App\Http\Requests\UlasanRequest;
use App\Models\PesananJasa;
use App\Models\UlasanJasa;
use Illuminate\Http\Request;

/**
 * Controller PelangganUlasanController
 *
 * Mengelola pengiriman ulasan/review oleh pelanggan setelah pesanan selesai.
 *
 * Aturan bisnis:
 *  1. Hanya pesanan dengan status 'selesai' yang bisa diulas
 *  2. Setiap pesanan hanya bisa diulas SATU KALI (cek duplikat)
 *  3. Rating menggunakan skala 1–5 bintang
 *  4. Komentar bersifat opsional, maks 1000 karakter
 */
class PelangganUlasanController extends Controller
{
    /**
     * Simpan ulasan baru untuk pesanan yang sudah selesai.
     *
     * Validasi berlapis:
     *  Layer 1 (UlasanRequest): format data — rating 1-5, id_pesanan exists
     *  Layer 2 (dalam method): aturan bisnis — pesanan harus milik pelanggan ini,
     *                           statusnya harus 'selesai', dan belum pernah diulas
     */
    public function store(UlasanRequest $request)
    {
        $data = $request->validated();

        // Verifikasi kepemilikan dan status pesanan secara bersamaan.
        // Kondisi: id_pelanggan = pengguna login AND id_pesanan sesuai AND status = selesai.
        // Menggunakan firstOrFail() agar otomatis 404 jika tidak ditemukan.
        $pesanan = PesananJasa::where('id_pelanggan', $request->user()->id_pengguna)
            ->where('id_pesanan', $data['id_pesanan'])
            ->where('status_pesanan', 'selesai') // Hanya pesanan selesai yang bisa diulas
            ->firstOrFail();

        // Cegah ulasan duplikat: cek apakah pesanan sudah pernah diulas sebelumnya
        if ($pesanan->ulasan) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        // Simpan ulasan baru ke database
        UlasanJasa::create([
            'id_pesanan'      => $pesanan->id_pesanan,
            'rating'          => $data['rating'],
            'komentar_ulasan' => $data['komentar_ulasan'],
            'tanggal_ulasan'  => now(), // Tanggal hari ini
        ]);

        return back()->with('success', 'Ulasan berhasil dikirimkan. Terima kasih!');
    }
}
