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

    // Form tambah admin berupa modal di halaman index, jadi create() cukup
    // mengarahkan kembali ke index (tidak ada halaman terpisah).
    public function create()
    {
        return redirect()->route('admin.pengguna.index');
    }

    /**
     * Simpan admin baru.
     *
     * Hanya membuat akun berperan 'admin' dengan status 'diverifikasi'.
     * Password otomatis di-hash oleh cast 'hashed' pada model Pengguna.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:150', 'unique:pengguna,email'],
            'no_telepon'   => ['nullable', 'string', 'max:20'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email ini sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        Pengguna::create([
            'nama_lengkap'      => $validated['nama_lengkap'],
            'email'             => $validated['email'],
            'no_telepon'        => $validated['no_telepon'] ?? null,
            'password'          => $validated['password'], // auto-hash via cast 'hashed'
            'peran'             => 'admin',
            'status_verifikasi' => 'diverifikasi',
        ]);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Admin baru berhasil ditambahkan.');
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
