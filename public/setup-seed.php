<?php

/**
 * PERINGATAN: Hapus file ini setelah digunakan!
 */

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Artisan::call('db:seed', ['--force' => true]);
echo '<pre>' . Artisan::output() . '</pre>';
echo 'Selesai.';
