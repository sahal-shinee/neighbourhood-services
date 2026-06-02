<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controller AdminPenggunaController
 *
 * Mengelola CRUD pengguna oleh admin melalui panel admin.
 *
 * Catatan implementasi:
 *  - create(), store(), show(), edit(), update() belum diimplementasikan penuh
 *    karena admin mengelola pengguna melalui halaman verifikasi dan halaman pengguna
 *    menggunakan modal di index, bukan halaman terpisah.
 *  - destroy() sudah diimplementasikan dengan pengaman: admin tidak bisa menghapus
 *    akun admin lain.
 */
class AdminPenggunaController extends Controller
{
    /**
     * Daftar semua pengguna dengan filter peran dan pencarian nama/email.
     *
     * Mendukung query parameter:
     *  - peran  : filter berdasarkan peran (admin/penyedia/pelanggan)
     *  - search : pencarian teks pada nama_lengkap atau email
     */
    public function index(Request $request)
    {
        $query = Pengguna::query();

        // Filter berdasarkan peran jika dipilih di dropdown filter
        if ($request->has('peran') && $request->peran != '') {
            $query->where('peran', $request->peran);
        }

        // Pencarian teks bebas pada nama atau email (case-insensitive LIKE)
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Paginate 10 data per halaman, urutkan terbaru di atas
        $pengguna = $query->latest()->paginate(10);
        return view('admin.pengguna.index', compact('pengguna'));
    }

    // Metode create() dan store() tidak diimplementasikan —
    // pembuatan pengguna baru dilakukan melalui halaman registrasi publik
    public function create()
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.pengguna.index');
    }

    // Metode show(), edit(), update() belum diimplementasikan — redirect ke index
    // agar tidak mengembalikan null (yang menyebabkan error 500)
    public function show($id)
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function edit($id)
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.pengguna.index');
    }

    /**
     * Hapus akun pengguna secara permanen.
     *
     * Pengaman: akun admin tidak bisa dihapus untuk mencegah sistem kehilangan
     * akses admin terakhir.
     */
    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        // Cegah penghapusan akun admin
        if ($pengguna->isAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus admin.');
        }

        $pengguna->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
