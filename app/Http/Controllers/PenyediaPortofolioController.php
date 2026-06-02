<?php

namespace App\Http\Controllers;

use App\Models\PortofolioJasa;
use App\Traits\CompressesImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller PenyediaPortofolioController
 *
 * Mengelola portofolio karya/proyek yang ditampilkan penyedia.
 *
 * Portofolio berfungsi sebagai bukti pengalaman kerja penyedia
 * yang ditampilkan di halaman profil publik mereka,
 * membantu pelanggan memutuskan untuk memesan.
 *
 * Catatan: show(), edit(), update() tidak diimplementasikan
 * karena manajemen portofolio menggunakan modal di halaman index.
 */
class PenyediaPortofolioController extends Controller
{
    use CompressesImages;
    /**
     * Tampilkan daftar semua portofolio milik penyedia yang sedang login.
     */
    public function index(Request $request)
    {
        $portofolio = $request->user()->portofolio()->latest()->get();
        return view('penyedia.portofolio.index', compact('portofolio'));
    }

    // create() tidak diimplementasikan — menggunakan modal di halaman index
    public function create()
    {
        // Proses pembuatan menggunakan modal di halaman index
    }

    /**
     * Simpan item portofolio baru.
     *
     * Validasi file foto:
     *  - Wajib ada (portofolio tanpa foto kurang bermakna)
     *  - Format: jpg, jpeg, png, webp
     *  - Ukuran maksimal: 2MB
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_proyek'     => 'required|string|max:150',
            'deskripsi_proyek' => 'nullable|string',
            'foto_proyek'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        PortofolioJasa::create([
            'id_penyedia'      => $request->user()->id_pengguna,
            'judul_proyek'     => $request->judul_proyek,
            'deskripsi_proyek' => $request->deskripsi_proyek,
            'foto_proyek' => $this->simpanGambarTerkompres($request->file('foto_proyek'), 'portofolio'),
        ]);

        return back()->with('success', 'Portofolio berhasil ditambahkan.');
    }

    // show(), edit(), update() tidak diimplementasikan dalam scope ini
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}

    /**
     * Hapus item portofolio beserta foto proyeknya dari storage.
     * Memverifikasi kepemilikan: penyedia hanya bisa hapus portofolio miliknya sendiri.
     */
    public function destroy(Request $request, $id)
    {
        // Pastikan portofolio yang dihapus adalah milik penyedia yang sedang login
        $portofolio = PortofolioJasa::where('id_penyedia', $request->user()->id_pengguna)->findOrFail($id);

        // Hapus file foto dari storage agar tidak ada file yang tertinggal
        if ($portofolio->foto_proyek) {
            Storage::disk('public')->delete($portofolio->foto_proyek);
        }

        $portofolio->delete();

        return back()->with('success', 'Portofolio berhasil dihapus.');
    }
}
