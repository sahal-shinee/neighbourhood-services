<?php

namespace App\Notifications;

use App\Models\Laporan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke PELANGGAN ketika admin menghapus laporan yang mereka kirimkan.
 * Memberi tahu pelanggan agar laporan tidak tiba-tiba hilang tanpa penjelasan.
 */
class LaporanDihapusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $alasan,
        public string $namaPenyedia
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul'  => 'Laporan Dihapus Admin',
            'pesan'  => "Laporan Anda terhadap \"{$this->namaPenyedia}\" dengan alasan \"{$this->alasan}\" telah dihapus oleh admin.",
            'url'    => route('pelanggan.laporan.index'),
            'tipe'   => 'warning',
        ];
    }
}
