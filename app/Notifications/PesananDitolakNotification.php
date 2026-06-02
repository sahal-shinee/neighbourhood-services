<?php

namespace App\Notifications;

use App\Models\PesananJasa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification PesananDitolakNotification
 *
 * Dikirim ke PELANGGAN dalam dua skenario:
 *  1. Penyedia secara aktif menolak pesanan (PenyediaPesananController::tolak())
 *  2. Pesanan otomatis dibatalkan karena bentrok jadwal dengan pesanan lain
 *     yang disetujui penyedia (PenyediaPesananController::setujui() → auto-cancel)
 *
 * Tujuan: memberi tahu pelanggan bahwa pesanannya tidak bisa dilayani,
 * sehingga mereka bisa mencari penyedia lain atau memilih waktu berbeda.
 */
class PesananDitolakNotification extends Notification
{
    use Queueable;

    public function __construct(public PesananJasa $pesanan) {}

    /**
     * Gunakan channel database untuk menyimpan notifikasi.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data notifikasi.
     * URL mengarah ke halaman detail pesanan pelanggan untuk melihat status terkini.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul'      => 'Pesanan Dibatalkan',
            'pesan'      => "Pesanan Anda untuk layanan \"{$this->pesanan->jasa->nama_jasa}\" telah ditolak/dibatalkan oleh penyedia.",
            'url'        => route('pelanggan.pesanan.show', $this->pesanan->id_pesanan),
            'tipe'       => 'error', // Warna merah di UI notifikasi
            'id_pesanan' => $this->pesanan->id_pesanan,
        ];
    }
}
