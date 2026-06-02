<?php

namespace App\Notifications;

use App\Models\PesananJasa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification PesananSelesaiNotification
 *
 * Dikirim ke PELANGGAN ketika penyedia menandai pesanan sebagai selesai dikerjakan.
 *
 * Tujuan: memberi tahu pelanggan bahwa pekerjaan sudah selesai dan mengajak
 * mereka untuk memberikan ulasan/rating terhadap layanan yang diterima.
 *
 * Dipanggil dari: PenyediaPesananController::selesai()
 */
class PesananSelesaiNotification extends Notification
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
     * URL mengarah ke halaman detail pesanan di mana pelanggan bisa memberikan ulasan.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul'      => 'Pesanan Selesai',
            // Pesan mengajak pelanggan untuk menulis ulasan
            'pesan'      => "Pesanan layanan \"{$this->pesanan->jasa->nama_jasa}\" telah selesai. Berikan ulasan Anda!",
            'url'        => route('pelanggan.pesanan.show', $this->pesanan->id_pesanan),
            'tipe'       => 'info',
            'id_pesanan' => $this->pesanan->id_pesanan,
        ];
    }
}
