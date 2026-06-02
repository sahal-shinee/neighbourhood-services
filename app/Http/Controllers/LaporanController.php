<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Pengguna;
use App\Models\PesananJasa;
use App\Traits\CompressesImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller LaporanController
 *
 * Mengelola fitur laporan pelanggaran dari sisi pelanggan:
 *  - Melihat riwayat laporan yang pernah dikirim (dengan status tracking)
 *  - Form pembuatan laporan terhadap penyedia tertentu
 *  - Menyimpan laporan baru ke database
 */
class LaporanController extends Controller
{
    use CompressesImages;
    /**
     * Tampilkan riwayat semua laporan yang pernah dikirim oleh pelanggan ini.
     *
     * Fitur transparansi: pelanggan bisa memantau status laporan mereka
     * (baru → ditinjau → ditindaklanjuti/ditolak) tanpa harus menghubungi admin.
     *
     * Laporan diurutkan terbaru di atas, dipaginasi 10 per halaman.
     */
    public function index(Request $request)
    {
        $laporan = Laporan::where('id_pelapor', $request->user()->id_pengguna)
            ->with('penyedia') // Eager load data penyedia untuk ditampilkan di kartu
            ->latest()
            ->paginate(10);

        return view('pelanggan.laporan.index', compact('laporan'));
    }

    /**
     * Tampilkan form pembuatan laporan terhadap penyedia tertentu.
     *
     * @param int $id  ID pengguna penyedia yang akan dilaporkan
     *
     * Data yang dikirim ke view:
     *  - $penyedia : data profil penyedia yang dilaporkan
     *  - $pesananList : daftar pesanan pelanggan dengan penyedia ini (untuk referensi)
     */
    public function create($id)
    {
        // Pastikan yang dilaporkan adalah penyedia terverifikasi yang valid
        $penyedia = Pengguna::penyedia()->verified()->findOrFail($id);

        // Ambil daftar pesanan pelanggan dengan penyedia ini sebagai konteks opsional
        // Hanya pesanan yang sudah 'selesai' atau 'dibatalkan' yang relevan sebagai bukti
        $pesananList = PesananJasa::where('id_pelanggan', auth()->user()->id_pengguna)
            ->whereHas('jasa', function($q) use ($id) {
                $q->where('id_penyedia', $id);
            })
            ->whereIn('status_pesanan', ['selesai', 'dibatalkan'])
            ->with('jasa')
            ->latest()
            ->get();

        return view('pelanggan.laporan.create', compact('penyedia', 'pesananList'));
    }

    /**
     * Simpan laporan baru ke database.
     *
     * Validasi data:
     *  - id_penyedia   : wajib, harus ada di tabel pengguna
     *  - id_pesanan    : opsional, referensi pesanan terkait
     *  - alasan        : wajib, pilihan preset (maks 100 karakter)
     *  - detail_laporan: wajib, min 20 karakter agar tidak asal lapor
     *  - bukti_foto    : opsional, image maks 3MB
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_penyedia'    => ['required', 'exists:pengguna,id_pengguna'],
            'id_pesanan'     => ['nullable', 'exists:pesanan_jasa,id_pesanan'],
            'alasan'         => ['required', 'string', 'max:100'],
            'detail_laporan' => ['required', 'string', 'min:20'],
            'bukti_foto'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        // Upload dan kompres foto bukti jika ada
        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $this->simpanGambarTerkompres(
                $request->file('bukti_foto'), 'laporan', 'public', 1600, 75
            );
        }

        // Tambahkan ID pelapor (pelanggan yang sedang login)
        $data['id_pelapor'] = $request->user()->id_pengguna;
        // Status awal laporan: 'baru' — menunggu ditinjau admin
        $data['status']     = 'baru';

        Laporan::create($data);

        return redirect()->route('pelanggan.laporan.index')
            ->with('success', 'Laporan berhasil dikirim. Admin akan segera meninjau laporan Anda.');
    }
}
