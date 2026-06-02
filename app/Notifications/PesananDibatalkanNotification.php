<?php

namespace App\Notifications;

use App\Models\PesananJasa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification PesananDibatalkanNotification
 *
 * Dikirim ke PENYEDIA ketika pelanggan membatalkan pesanan mereka sendiri.
 *
 * Tujuan: memberi tahu penyedia bahwa slot jadwal mereka kini kosong kembali
 * karena pelanggan memutuskan untuk membatalkan pesanan yang masih menunggu.
 *
 * Dipanggil dari: PelangganPesananController::batal()
 *
 * Catatan: ini berbeda dari PesananDitolakNotification yang dikirim ke pelanggan.
 * Notification ini dikirim ke PENYEDIA (arah sebaliknya).
 */
class PesananDibatalkanNotification extends Notification
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
     * URL mengarah ke halaman daftar pesanan penyedia.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul'      => 'Pesanan Dibatalkan Pelanggan',
            // Pesan menyebutkan nama pelanggan agar penyedia tahu siapa yang membatalkan
            'pesan'      => "\"{$this->pesanan->pelanggan->nama_lengkap}\" membatalkan pesanan layanan \"{$this->pesanan->jasa->nama_jasa}\".",
            'url'        => route('penyedia.pesanan.index'),
            'tipe'       => 'warning', // Warna kuning/amber di UI notifikasi
            'id_pesanan' => $this->pesanan->id_pesanan,
        ];
    }
}
