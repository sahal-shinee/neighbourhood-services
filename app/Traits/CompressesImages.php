<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

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
        $image = Image::read($file->getPathname());
        $image->scaleDown(width: $maxWidth);
        $encoded = $image->toJpeg($quality);

        // Simpan ke disk yang ditentukan
        Storage::disk($disk)->put($filename, $encoded);

        return $filename;
    }
}
