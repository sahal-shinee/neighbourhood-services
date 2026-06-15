<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Trait CompressesImages
 *
 * Menyediakan helper untuk mengompresi dan menyimpan gambar yang diupload.
 * Mencegah foto berukuran besar (misalnya 10MB) disimpan apa adanya.
 *
 * Standar kompresi:
 *  - Foto publik (jasa, profil, portofolio) : maks 1200px lebar, kualitas 80%
 *  - Foto bukti laporan                     : maks 1600px lebar, kualitas 75%
 *  - Foto KTP (private)                     : maks 1600px lebar, kualitas 85% (harus tetap jelas)
 */
trait CompressesImages
{
    /**
     * Kompresi dan simpan gambar ke disk tertentu.
     *
     * @param UploadedFile $file      File yang diupload
     * @param string       $folder    Subfolder tujuan (misal: 'profil', 'jasa')
     * @param string       $disk      Disk storage: 'public' atau 'local'
     * @param int          $maxWidth  Lebar maksimal dalam piksel
     * @param int          $quality   Kualitas JPEG 1-100 (lebih kecil = lebih kecil file)
     * @return string  Path relatif file yang disimpan (untuk disimpan ke database)
     */
    protected function simpanGambarTerkompres(
        UploadedFile $file,
        string $folder,
        string $disk = 'public',
        int $maxWidth = 1200,
        int $quality = 80
    ): string {
        // Buat nama file unik dengan ekstensi .jpg (semua dikonversi ke JPEG)
        $filename = $folder . '/' . Str::uuid() . '.jpg';

        // Baca file, scale down jika lebih lebar dari maxWidth, encode ke JPEG
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());
        $image->scaleDown(width: $maxWidth);
        // Cast eksplisit ke string agar adapter penyimpanan selalu menerima konten valid
        $encoded = (string) $image->toJpeg($quality);

        // Simpan ke disk yang ditentukan.
        // PENTING: disk 'local' dikonfigurasi 'throw' => false, sehingga put() akan
        // mengembalikan false (bukan melempar error) jika gagal menulis — misalnya
        // ketika folder storage tidak punya izin tulis di server hosting.
        // Kita cek nilai return-nya supaya kegagalan tidak "diam-diam" membuat
        // record database dengan path file yang sebenarnya tidak pernah tersimpan.
        $berhasil = Storage::disk($disk)->put($filename, $encoded);

        if ($berhasil === false) {
            throw new \RuntimeException(
                "Gagal menyimpan gambar ke disk '{$disk}' (folder: {$folder}). " .
                "Pastikan folder 'storage' memiliki izin tulis di server (chmod 775) " .
                "dan dimiliki oleh user web server."
            );
        }

        return $filename;
    }
}
