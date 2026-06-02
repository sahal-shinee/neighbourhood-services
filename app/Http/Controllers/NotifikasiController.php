<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller NotifikasiController
 *
 * Mengelola notifikasi pengguna yang menggunakan Laravel Database Notifications.
 * Notifikasi disimpan di tabel 'notifications' (bawaan Laravel) dan ditandai
 * sebagai dibaca menggunakan kolom 'read_at'.
 *
 * Tipe notifikasi yang ada di sistem:
 *  - PesananBaruNotification      : untuk penyedia, saat pelanggan memesan
 *  - PesananDisetujuiNotification : untuk pelanggan, saat penyedia menyetujui
 *  - PesananSelesaiNotification   : untuk pelanggan, saat penyedia menandai selesai
 *  - PesananDitolakNotification   : untuk pelanggan, saat penyedia menolak
 *  - PesananDibatalkanNotification: untuk penyedia, saat pelanggan membatalkan
 */
class NotifikasiController extends Controller
{
    /**
     * Tandai semua notifikasi yang belum dibaca sebagai sudah dibaca.
     * Mengupdate kolom 'read_at' ke timestamp saat ini untuk semua notifikasi unread.
     */
    public function bacaSemua(Request $request)
    {
        // markAsRead() adalah method bawaan Laravel Notifiable trait
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Tandai satu notifikasi sebagai dibaca dan arahkan ke URL terkait.
     *
     * Alur:
     *  1. Cari notifikasi berdasarkan ID (milik pengguna yang login)
     *  2. Tandai sebagai dibaca
     *  3. Ekstrak URL dari data notifikasi
     *  4. Redirect ke URL tersebut (misal: halaman detail pesanan)
     *     atau kembali ke halaman sebelumnya jika tidak ada URL
     */
    public function baca(Request $request, $id)
    {
        $notifikasi = $request->user()->notifications()->findOrFail($id);
        $notifikasi->markAsRead();

        // Data notifikasi disimpan sebagai JSON — ambil URL redirect jika ada
        $data = $notifikasi->data;
        $url  = $data['url'] ?? null;

        if ($url) {
            return redirect($url);
        }

        return back();
    }

    /**
     * Hapus semua notifikasi yang sudah dibaca.
     * Menjaga tabel notifications tetap bersih dari data lama.
     */
    public function hapusSemua(Request $request)
    {
        $request->user()->readNotifications()->delete();
        return back()->with('success', 'Notifikasi yang sudah dibaca berhasil dihapus.');
    }
}
