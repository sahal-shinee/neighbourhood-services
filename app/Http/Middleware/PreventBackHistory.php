<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware PreventBackHistory
 *
 * Menambahkan HTTP header Cache-Control ke setiap response dari rute yang dilindungi autentikasi.
 *
 * Tujuan utama:
 *   Mencegah browser menyimpan cache halaman yang memerlukan login.
 *   Setelah pengguna logout, menekan tombol "Back" di browser tidak akan
 *   menampilkan kembali halaman dashboard yang sudah dilindungi.
 *
 * Header yang ditambahkan:
 *   - Cache-Control: no-store, no-cache, must-revalidate, max-age=0
 *     → Browser wajib selalu meminta halaman ke server, tidak boleh gunakan cache
 *   - Pragma: no-cache
 *     → Kompatibilitas dengan HTTP/1.0
 *   - Expires: 0
 *     → Tandai bahwa respons sudah kedaluwarsa (expired) langsung
 *
 * Middleware ini didaftarkan untuk semua rute dalam grup middleware 'auth'
 * di file bootstrap/app.php.
 */
class PreventBackHistory
{
    /**
     * Tangani request masuk: teruskan ke controller, lalu tambahkan header no-cache ke response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Proses request ke controller terlebih dahulu, dapatkan response-nya
        $response = $next($request);

        // Tambahkan header no-cache via $response->headers->set().
        // Memakai ->headers->set() (bukan ->header()) supaya kompatibel dengan SEMUA
        // jenis response — termasuk StreamedResponse/BinaryFileResponse yang dipakai
        // untuk menyajikan file (mis. foto KTP). Method ->header() hanya ada di
        // Illuminate\Http\Response, sehingga akan error fatal pada StreamedResponse.
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}
