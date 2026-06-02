<?php

/**
 * routes/console.php — Definisi perintah Artisan dan jadwal task otomatis
 *
 * File ini mendaftarkan:
 *  1. Perintah Artisan kustom (closure-based atau class-based)
 *  2. Jadwal task yang dijalankan secara otomatis (Task Scheduler)
 *
 * Untuk menjalankan scheduler di production, tambahkan cron job berikut ke server:
 *   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
 *
 * Di development, jalankan secara manual:
 *   php artisan schedule:run
 * atau
 *   php artisan schedule:work (untuk menjalankan terus-menerus di background)
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Perintah bawaan Laravel: menampilkan quote inspirasi di terminal
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Commands — Jadwal Task Otomatis
|--------------------------------------------------------------------------
|
| laporan:purge
|
| Tugas: Hapus laporan yang sudah diselesaikan (status 'ditindaklanjuti' atau
| 'ditolak') secara otomatis setelah 30 hari dari tanggal updated_at.
|
| Tujuan: Menjaga database tetap bersih dari data laporan lama yang sudah
| tidak relevan, sekaligus menghapus file bukti foto agar tidak menumpuk
| di storage.
|
| Jadwal: Setiap hari pukul 03:00 pagi (pilih jam sepi traffic untuk
| mengurangi beban server).
|
| Output log: dicatat ke storage/logs/laporan-purge.log untuk audit.
|
| Uji coba manual (preview saja, tidak hapus data):
|   php artisan laporan:purge --dry-run
|
| Ubah threshold hari:
|   php artisan laporan:purge --days=7
|
*/
// Hapus notifikasi yang sudah dibaca dan berumur lebih dari 30 hari
// agar tabel notifications tidak membengkak
Schedule::call(function () {
    \DB::table('notifications')
        ->whereNotNull('read_at')
        ->where('read_at', '<', now()->subDays(30))
        ->delete();
})->dailyAt('03:30')->name('purge-old-notifications');

Schedule::command('laporan:purge')->dailyAt('03:00')
         ->appendOutputTo(storage_path('logs/laporan-purge.log')) // Catat hasil eksekusi ke file log
         ->onSuccess(function () {
             logger('laporan:purge selesai.'); // Log singkat ke Laravel log jika berhasil
         });
