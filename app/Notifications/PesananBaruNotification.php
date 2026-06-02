<?php

namespace App\Notifications;

use App\Models\PesananJasa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification PesananBaruNotification
 *
 * Dikirim ke PENYEDIA ketika pelanggan baru saja membuat pesanan untuk jasanya.
 *
 * Tujuan: memberi tahu penyedia ada pesanan baru yang menunggu disetujui/ditolak.
 * Notifikasi disimpan di database (tabel 'notifications') dan ditampilkan
 * di icon lonceng di navbar penyedia secara real-time.
 *
 * Driver yang digunakan: 'database' (disimpan di tabel, bukan email/SMS/push)
 */
class PesananBaruNotification extends Notification
{
    use Queueable; // Memungkinkan notifikasi diproses di background queue (jika queue dikonfigurasi)

    /**
     * Konstruktor: terima data pesanan yang dikirim sebagai notifikasi.
     * PHP 8 constructor property promotion untuk singkatnya deklarasi property.
     */
    public function __construct(public PesananJasa $pesanan) {}

    /**
     * Tentukan channel pengiriman notifikasi.
     * 'database' = disimpan di tabel notifications, bukan email/SMS.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan ke kolom 'data' (JSON) di tabel notifications.
     *
     * Field 'url' digunakan oleh NotifikasiController::baca() untuk redirect
     * pengguna ke halaman yang relevan saat notifikasi diklik.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'judul'      => 'Pesanan Baru Masuk',
            'pesan'      => "\"{$this->pesanan->pelanggan->nama_lengkap}\" memesan layanan \"{$this->pesanan->jasa->nama_jasa}\" Anda.",
            'url'        => route('penyedia.pesanan.index'), // Arahkan ke halaman daftar pesanan penyedia
            'tipe'       => 'info',
            'id_pesanan' => $this->pesanan->id_pesanan,
        ];
    }
}
