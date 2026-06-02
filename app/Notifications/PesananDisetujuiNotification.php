<?php

namespace App\Notifications;

use App\Models\PesananJasa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification PesananDisetujuiNotification
 *
 * Dikirim ke PELANGGAN ketika penyedia menyetujui pesanan yang dikirim.
 *
 * Tujuan: memberi tahu pelanggan bahwa pesanannya sudah diterima dan pekerjaan
 * akan segera dilaksanakan sesuai jadwal yang disepakati.
 *
 * Dipanggil dari: PenyediaPesananController::setujui()
 */
class PesananDisetujuiNotification extends Notification
{
    use Queueable;

    public function __construct(public PesananJasa $pesanan) {}

    /**
     * Gunakan channel database (notifikasi tersimpan, bukan dikirim via email).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data notifikasi yang disimpan sebagai JSON di database.
     * URL mengarah ke halaman detail pesanan pelanggan yang bersangkutan.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul'      => 'Pesanan Disetujui',
            'pesan'      => "Pesanan Anda untuk layanan \"{$this->pesanan->jasa->nama_jasa}\" telah disetujui oleh penyedia.",
            'url'        => route('pelanggan.pesanan.show', $this->pesanan->id_pesanan),
            'tipe'       => 'success', // Warna hijau di UI notifikasi
            'id_pesanan' => $this->pesanan->id_pesanan,
        ];
    }
}
