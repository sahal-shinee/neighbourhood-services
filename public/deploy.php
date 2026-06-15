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

// ─── Bersihkan cache Laravel agar perubahan kode langsung terbaca ───
$cacheGlobs = [
    __DIR__ . '/../bootstrap/cache/*.php',        // config/route/services cache
    __DIR__ . '/../storage/framework/views/*.php', // compiled Blade views (WAJIB, agar tampilan ter-update)
    __DIR__ . '/../storage/framework/cache/data/*', // application cache
];

$cleared = 0;
foreach ($cacheGlobs as $pattern) {
    foreach (glob($pattern) ?: [] as $cacheFile) {
        if (is_file($cacheFile) && @unlink($cacheFile)) {
            $cleared++;
        }
    }
}

// ─── Pastikan folder upload & cache ADA dan BISA DITULIS ───
// Tanpa ini, upload foto (KTP ke storage/app/private, portfolio/jasa ke public/storage)
// akan gagal diam-diam di server karena folder tidak punya izin tulis.
// Karena deploy.php berjalan sebagai user PHP yang memiliki file hasil ekstraksi,
// chmod di sini berhasil tanpa perlu akses SSH.
$base = realpath(__DIR__ . '/..');

// Buat folder jika belum ada, lalu set izin tulis 0775
$ensureWritable = static function (string $dir): void {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    @chmod($dir, 0775);
};

// Set izin 0775 ke folder ini DAN seluruh subfolder di dalamnya secara rekursif
$chmodRecursive = static function (string $root) use (&$chmodRecursive): void {
    if (!is_dir($root)) {
        return;
    }
    @chmod($root, 0775);
    foreach (@scandir($root) ?: [] as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $root . '/' . $item;
        if (is_dir($path)) {
            $chmodRecursive($path);
        }
    }
};

// Folder wajib-tulis (dibuat jika hilang setelah ekstraksi)
$requiredDirs = [
    $base . '/storage/app/private',          // foto KTP (disk 'local')
    $base . '/storage/app/public',           // cadangan upload publik
    $base . '/storage/framework/cache/data',
    $base . '/storage/framework/sessions',
    $base . '/storage/framework/views',
    $base . '/storage/logs',
    $base . '/bootstrap/cache',
    $base . '/public/storage',               // foto jasa/profil/portfolio (disk 'public')
];

foreach ($requiredDirs as $dir) {
    $ensureWritable($dir);
}

// Pastikan seluruh pohon folder storage bisa ditulis
$chmodRecursive($base . '/storage');
$chmodRecursive($base . '/public/storage');

// ─── SELF-TEST: benar-benar coba tulis file ke folder upload ───
// Hasilnya ditulis ke public/_deploy_health.txt agar bisa diperiksa dari browser.
// Ini membuktikan apakah upload foto akan berhasil ATAU folder masih tidak bisa ditulis.
$health = [];
$health[] = 'Deploy health check — ' . date('Y-m-d H:i:s');
if (function_exists('posix_geteuid') && function_exists('posix_getpwuid')) {
    $health[] = 'PHP user   : ' . (posix_getpwuid(posix_geteuid())['name'] ?? '?');
} else {
    $health[] = 'PHP user   : ' . get_current_user();
}
$health[] = 'storage_path: ' . $base . '/storage/app/private';
$health[] = '';

$selfTestTargets = [
    'KTP   (disk local)  ' => $base . '/storage/app/private/ktp',
    'Foto  (disk public) ' => $base . '/public/storage',
];
foreach ($selfTestTargets as $label => $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $ada      = is_dir($dir) ? 'ADA       ' : 'TIDAK-ADA ';
    $bisa     = is_writable($dir) ? 'WRITABLE     ' : 'NOT-WRITABLE ';
    $testFile = $dir . '/_writetest_' . uniqid() . '.tmp';
    $tulis    = @file_put_contents($testFile, 'ok');
    $hasil    = ($tulis !== false) ? 'TES-TULIS: SUKSES' : 'TES-TULIS: GAGAL';
    if ($tulis !== false) {
        @unlink($testFile);
    }
    $health[] = $label . ': ' . $ada . $bisa . $hasil;
}

// Hitung jumlah file KTP yang benar-benar ada di server saat ini
$ktpDir   = $base . '/storage/app/private/ktp';
$ktpFiles = is_dir($ktpDir) ? array_filter(scandir($ktpDir) ?: [], fn ($f) => !in_array($f, ['.', '..'], true)) : [];
$health[] = '';
$health[] = 'Jumlah file KTP tersimpan di server: ' . count($ktpFiles);

@file_put_contents($base . '/public/_deploy_health.txt', implode("\n", $health) . "\n");

http_response_code(200);
echo 'OK: Deploy berhasil (' . $fileCount . ' file, ' . $cleared . ' cache dibersihkan, izin folder upload diset) pada ' . date('Y-m-d H:i:s');
