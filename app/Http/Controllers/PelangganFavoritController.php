<?php

namespace App\Http\Controllers;

use App\Models\FavoritJasa;
use App\Models\Jasa;
use Illuminate\Http\Request;

/**
 * Controller PelangganFavoritController
 *
 * Mengelola fitur simpan/favorit jasa oleh pelanggan.
 * Pelanggan bisa menandai jasa sebagai favorit dari halaman pencarian atau
 * detail penyedia, lalu melihat semua jasa favorit di halaman khusus.
 */
class PelangganFavoritController extends Controller
{
    /**
     * Tampilkan daftar semua jasa yang difavoritkan pelanggan.
     */
    public function index(Request $request)
    {
        $favorit = FavoritJasa::where('id_pelanggan', $request->user()->id_pengguna)
            ->with(['jasa.penyedia'])
            ->latest()
            ->paginate(12);

        // Hitung rating untuk setiap jasa yang difavoritkan
        $favorit->getCollection()->transform(function ($item) {
            if ($item->jasa) {
                $item->jasa->rating = $item->jasa->penyedia
                    ? $item->jasa->penyedia->rating_rata_rata
                    : 0;
            }
            return $item;
        });

        return view('pelanggan.favorit.index', compact('favorit'));
    }

    /**
     * Toggle favorit: tambah jika belum ada, hapus jika sudah ada.
     * Mengembalikan JSON agar bisa dikonsumsi oleh Alpine.js tanpa reload halaman.
     */
    public function toggle(Request $request, $jasaId)
    {
        $user = $request->user();

        // Pastikan jasa yang difavoritkan ada dan aktif
        $jasa = Jasa::where('is_aktif', true)->findOrFail($jasaId);

        $existing = FavoritJasa::where('id_pelanggan', $user->id_pengguna)
            ->where('id_jasa', $jasaId)
            ->first();

        if ($existing) {
            // Sudah difavoritkan → hapus (unfavorit)
            $existing->delete();
            $isFavorit = false;
            $message   = 'Jasa dihapus dari favorit.';
        } else {
            // Belum difavoritkan → tambah
            FavoritJasa::create([
                'id_pelanggan' => $user->id_pengguna,
                'id_jasa'      => $jasa->id_jasa,
            ]);
            $isFavorit = true;
            $message   = 'Jasa disimpan ke favorit!';
        }

        // Kembalikan JSON untuk Alpine.js update UI secara langsung
        return response()->json([
            'is_favorit' => $isFavorit,
            'message'    => $message,
        ]);
    }
}
