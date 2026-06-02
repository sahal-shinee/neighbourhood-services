<?php

/**
 * PERINGATAN: Hapus file ini setelah digunakan!
 * Akses file ini hanya sekali setelah deploy pertama atau setelah ada perubahan composer.json
 */

$zip_path    = __DIR__ . '/../vendor.zip';
$extract_path = __DIR__ . '/..';

if (!file_exists($zip_path)) {
    echo '<p style="font-family:sans-serif;color:orange;">⚠️ vendor.zip tidak ditemukan. Kemungkinan sudah diekstrak sebelumnya.</p>';
    exit;
}

echo '<p style="font-family:sans-serif;">⏳ Mengekstrak vendor.zip, harap tunggu...</p>';
flush();

$zip = new ZipArchive;

if ($zip->open($zip_path) === TRUE) {
    $zip->extractTo($extract_path);
    $zip->close();
    unlink($zip_path);
    echo '<p style="font-family:sans-serif;color:green;">✅ Selesai! vendor/ berhasil diekstrak.</p>';
    echo '<p style="font-family:sans-serif;color:red;"><strong>Segera hapus file ini dari File Manager cPanel!</strong></p>';
} else {
    echo '<p style="font-family:sans-serif;color:red;">❌ Gagal membuka vendor.zip. Coba upload ulang.</p>';
}
