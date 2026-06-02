<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

/**
 * Controller AdminKategoriController
 *
 * Mengelola CRUD kategori jasa oleh admin.
 *
 * Kategori digunakan sebagai:
 *  - Filter pencarian jasa oleh pelanggan
 *  - Pengelompokan jasa pada halaman landing
 *  - Pilihan dropdown saat penyedia membuat/mengedit jasa
 *
 * Catatan UI:
 *  create() dan edit() tidak mengembalikan view terpisah karena
 *  modal di halaman index digunakan untuk kedua aksi tersebut.
 */
class AdminKategoriController extends Controller
{
    /**
     * Tampilkan daftar semua kategori dengan paginasi.
     */
    public function index()
    {
        $kategori = Kategori::latest()->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    // create() tidak diimplementasikan — menggunakan modal di halaman index
    public function create()
    {
        // Proses pembuatan menggunakan modal di halaman index
    }

    /**
     * Simpan kategori baru ke database.
     *
     * Validasi:
     *  - nama_kategori: wajib, string, maks 100 karakter
     *  - ikon_kategori: opsional, string, maks 50 karakter (emoji atau ikon)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'ikon_kategori' => 'nullable|string|max:50',
        ]);

        Kategori::create($data);
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    // show() dan edit() tidak diimplementasikan — menggunakan modal
    public function show($id) {}
    public function edit($id) {}

    /**
     * Perbarui data kategori yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'ikon_kategori' => 'nullable|string|max:50',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($data);
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori secara permanen.
     * Catatan: jasa yang menggunakan kategori ini tidak otomatis terhapus.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
