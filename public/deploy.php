<?php

/**
 * deploy.php — Hook ekstraksi deployment
 *
 * Dipanggil otomatis oleh GitHub Actions setelah upload deploy.zip.
 * Alur: cek token → ekstrak deploy.zip → hapus zip → bersihkan cache.
 *
 * Keamanan: hanya bisa dijalankan jika token cocok dengan DEPLOY_TOKEN di .env server.
 * File ini AMAN dibiarkan di server (tanpa token akan menolak, tanpa zip tidak melakukan apa-apa).
 */

// ─── Baca nilai dari .env tanpa bootstrap Laravel (agar tetap jalan saat vendor/ sedang diganti) ───
function envValue(string $key): ?string
{
    $envPath = __DIR__ . '/../.env';
    if (!is_readable($envPath)) {
        return null;
    }
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (str_starts_with($line, $key . '=')) {
            return trim(substr($line, strlen($key) + 1), " \t\"'");
        }
    }
    return null;
}

header('Content-Type: text/plain; charset=utf-8');

// ─── Validasi token ───
$expectedToken = envValue('DEPLOY_TOKEN');
$providedToken = $_GET['token'] ?? '';

if (empty($expectedToken)) {
    http_response_code(500);
    exit('ERROR: DEPLOY_TOKEN belum diset di .env server.');
}

if (!is_string($providedToken) || !hash_equals($expectedToken, $providedToken)) {
    http_response_code(403);
    exit('ERROR: Token tidak valid.');
}

// ─── Ekstrak deploy.zip ───
$zipPath     = __DIR__ . '/../deploy.zip';
$extractPath = realpath(__DIR__ . '/..');

if (!file_exists($zipPath)) {
    http_response_code(404);
    exit('ERROR: deploy.zip tidak ditemukan di server.');
}

$zip = new ZipArchive;
if ($zip->open($zipPath) !== true) {
    http_response_code(500);
    exit('ERROR: Gagal membuka deploy.zip.');
}

if (!$zip->extractTo($extractPath)) {
    $zip->close();
    http_response_code(500);
    exit('ERROR: Gagal mengekstrak deploy.zip (cek izin folder).');
}

$fileCount = $zip->numFiles;
$zip->close();

// ─── Hapus zip setelah ekstraksi berhasil ───
@unlink($zipPath);

// ─── Bersihkan cache Laravel (config/route/services) agar perubahan terbaca ───
foreach (glob(__DIR__ . '/../bootstrap/cache/*.php') ?: [] as $cacheFile) {
    @unlink($cacheFile);
}

http_response_code(200);
echo 'OK: Deploy berhasil (' . $fileCount . ' file) pada ' . date('Y-m-d H:i:s');
