<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilRequest;
use App\Traits\CompressesImages;
use App\Models\Jasa;
use App\Models\Kategori;
use App\Models\Pengguna;
use App\Models\PesananJasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Controller PelangganController
 *
 * Mengelola fitur-fitur utama panel pelanggan:
 *  - Dashboard ringkasan pesanan
 *  - Pencarian dan penemuan jasa
 *  - Lihat profil publik penyedia
 *  - Kelola profil pelanggan
 */
class PelangganController extends Controller
{
    use CompressesImages;
    /**
     * Dashboard pelanggan — statistik pesanan dan daftar pesanan terbaru.
     */
    public function dashboard(Request $request)
    {
        $pelanggan = $request->user();

        $stats = [
            'total_pesanan'  => $pelanggan->pesananSebagaiPelanggan()->count(),
            // Pesanan aktif: masih menunggu persetujuan atau sedang dikerjakan
            'pesanan_aktif'  => $pelanggan->pesananSebagaiPelanggan()->whereIn('status_pesanan', ['menunggu', 'disetujui'])->count(),
            // Jumlah ulasan yang sudah pernah ditulis pelanggan ini
            'ulasan_ditulis' => $pelanggan->pesananSebagaiPelanggan()->has('ulasan')->count(),
        ];

        // 5 pesanan terbaru untuk widget aktivitas di dashboard
        $pesanan_terbaru = $pelanggan->pesananSebagaiPelanggan()->with('jasa.penyedia')->latest()->take(5)->get();

        return view('pelanggan.dashboard', compact('stats', 'pesanan_terbaru'));
    }

    /**
     * Halaman pencarian jasa — menampilkan semua jasa aktif dengan filter dan pengurutan.
     *
     * Desain sistem pencarian:
     *  - GPS BERSIFAT OPSIONAL: semua jasa ditampilkan meski tanpa lokasi
     *  - Koordinat GPS hanya digunakan untuk pengurutan 'terdekat'/'terjauh'
     *  - Jika GPS tidak aktif dan dipilih sort jarak → fallback ke urutan terbaru
     *
     * Harga termurah/termahal menggunakan "effective_price":
     *  - Untuk tipe 'paket'  : harga paket MINIMUM yang tersedia (subquery)
     *  - Untuk tipe lainnya  : nilai kolom tarif_per_jam
     */
    public function cari(Request $request)
    {
        $kategoriList = Kategori::all();
        $user         = $request->user();

        // Ambil koordinat dari form (GPS browser) atau profil pengguna sebagai fallback.
        // PENTING: cast ke float untuk mencegah SQL injection pada raw Haversine formula.
        $lat = $request->input('lat', $user?->latitude);
        $lng = $request->input('lng', $user?->longitude);
        $lat = $lat !== null && $lat !== '' ? (float) $lat : null;
        $lng = $lng !== null && $lng !== '' ? (float) $lng : null;
        $kategori = $request->input('kategori');
        $keyword  = $request->input('keyword');
        $sortBy   = $request->input('sort_by', 'default'); // default|terdekat|terjauh|termurah|termahal

        // Ekspresi SQL untuk menghitung harga efektif (terendah) per jasa.
        // Subquery MIN() untuk paket, langsung ambil tarif_per_jam untuk tipe lainnya.
        $effectivePrice = "CASE WHEN jasa.tipe_tarif = 'paket'
                               THEN (SELECT MIN(ph.harga) FROM paket_harga ph WHERE ph.id_jasa = jasa.id_jasa)
                               ELSE jasa.tarif_per_jam
                           END";

        // Subquery rating rata-rata langsung di SQL — menghindari N+1 query problem.
        // Menggabungkan ulasan → pesanan → jasa → pengguna dalam satu subquery.
        $avgRatingSubquery = "(
            SELECT COALESCE(AVG(uj.rating), 0)
            FROM ulasan_jasa uj
            INNER JOIN pesanan_jasa pj ON uj.id_pesanan = pj.id_pesanan
            INNER JOIN jasa j2 ON pj.id_jasa = j2.id_jasa
            WHERE j2.id_penyedia = pengguna.id_pengguna
        )";

        // Query dasar: join pengguna untuk mendapatkan data penyedia
        $query = Jasa::select('jasa.*',
                              'pengguna.nama_lengkap',
                              'pengguna.latitude   AS penyedia_lat',
                              'pengguna.longitude  AS penyedia_lng',
                              'pengguna.foto_profil',
                              DB::raw("({$effectivePrice}) AS effective_price"),
                              DB::raw("({$avgRatingSubquery}) AS rating"))
            ->join('pengguna', 'jasa.id_penyedia', '=', 'pengguna.id_pengguna')
            ->where('pengguna.status_verifikasi', 'diverifikasi') // Hanya penyedia terverifikasi
            ->where('pengguna.is_aktif', true)                   // Sembunyikan penyedia yang diblokir
            ->where('jasa.is_aktif', true);                      // Hanya jasa yang aktif

        // Terapkan filter kategori jika dipilih pengguna
        if ($kategori) {
            $query->where('jasa.kategori_jasa', $kategori);
        }

        // Pencarian keyword pada nama dan deskripsi jasa (OR search)
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('jasa.nama_jasa', 'like', "%{$keyword}%")
                  ->orWhere('jasa.deskripsi_jasa', 'like', "%{$keyword}%");
            });
        }

        // Tambahkan kolom jarak (formula Haversine) ke SELECT hanya jika ada koordinat.
        // Formula Haversine menghitung jarak antar dua titik GPS di permukaan bumi (km).
        // LEAST(1,...) mencegah error acos() akibat floating point di luar range [-1, 1].
        $hasLocation = $lat && $lng;
        if ($hasLocation) {
            $haversine = "(6371 * acos(LEAST(1, cos(radians({$lat})) * cos(radians(pengguna.latitude))
                          * cos(radians(pengguna.longitude) - radians({$lng}))
                          + sin(radians({$lat})) * sin(radians(pengguna.latitude)))))";
            $query->selectRaw("{$haversine} AS distance");
        }

        // Terapkan pengurutan menggunakan PHP match expression.
        // Jika sort jarak dipilih tapi GPS tidak tersedia → fallback ke terbaru.
        match ($sortBy) {
            'terdekat'  => $hasLocation ? $query->orderByRaw('distance ASC')  : $query->latest('jasa.created_at'),
            'terjauh'   => $hasLocation ? $query->orderByRaw('distance DESC') : $query->latest('jasa.created_at'),
            'termurah'  => $query->orderBy('effective_price', 'asc'),
            'termahal'  => $query->orderBy('effective_price', 'desc'),
            default     => $query->latest('jasa.created_at'),
        };

        // Paginasi 12 item per halaman, pertahankan query string di URL paginasi
        $jasa = $query->paginate(12)->withQueryString();

        // Ambil ID jasa yang sudah difavoritkan oleh pelanggan ini (untuk tombol favorit di kartu)
        $favoritIds = $user
            ? \App\Models\FavoritJasa::where('id_pelanggan', $user->id_pengguna)
                ->pluck('id_jasa')->toArray()
            : [];

        return view('pelanggan.cari', compact(
            'jasa', 'kategoriList', 'lat', 'lng',
            'kategori', 'keyword', 'sortBy', 'hasLocation', 'favoritIds'
        ));
    }

    /**
     * Tampilkan halaman profil publik penyedia.
     * Menampilkan jasa aktif, portofolio, dan semua ulasan yang diterima.
     */
    public function showPenyedia($id)
    {
        $penyedia = Pengguna::with(['jasaSebagaiPenyedia' => function($q) {
            $q->where('is_aktif', true); // Hanya jasa yang aktif ditampilkan
        }, 'portofolio'])->penyedia()->verified()->findOrFail($id);

        // Ambil ulasan dari semua pesanan yang ditujukan ke penyedia ini
        $ulasan = PesananJasa::whereHas('jasa', function($q) use ($id) {
            $q->where('id_penyedia', $id);
        })->has('ulasan')->with('ulasan', 'pelanggan')->latest()->get();

        return view('pelanggan.penyedia.show', compact('penyedia', 'ulasan'));
    }

    /**
     * Halaman "Semua Ulasan" penyedia dengan paginasi 3 per halaman.
     */
    public function ulasanPenyedia($id)
    {
        $penyedia = Pengguna::penyedia()->verified()->findOrFail($id);

        $ulasan = PesananJasa::whereHas('jasa', function($q) use ($id) {
            $q->where('id_penyedia', $id);
        })->has('ulasan')->with('ulasan', 'pelanggan')->latest()->paginate(3);

        return view('pelanggan.penyedia.ulasan', compact('penyedia', 'ulasan'));
    }

    /**
     * Tampilkan halaman profil pelanggan yang sedang login.
     */
    public function profil(Request $request)
    {
        $user = $request->user();
        return view('pelanggan.profil', compact('user'));
    }

    /**
     * Simpan perubahan data profil pelanggan.
     * Logika upload foto sama dengan controller penyedia.
     */
    public function updateProfil(ProfilRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // Upload dan ganti foto profil jika ada file baru
        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $this->simpanGambarTerkompres($request->file('foto_profil'), 'profil');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
