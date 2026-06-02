<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Http\Requests\RegisterRequest;
use App\Traits\CompressesImages;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller RegisteredUserController
 *
 * Mengelola proses pendaftaran akun baru.
 *
 * Logika pendaftaran berbeda berdasarkan peran yang dipilih:
 *  - Pelanggan : langsung diverifikasi (status_verifikasi = 'diverifikasi')
 *                karena tidak perlu validasi identitas
 *  - Penyedia  : status awal 'pending', harus menunggu persetujuan admin
 *                dan wajib mengunggah foto KTP sebagai syarat verifikasi
 */
class RegisteredUserController extends Controller
{
    use CompressesImages;
    /**
     * Tampilkan halaman form registrasi.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses data registrasi yang dikirim dari form.
     *
     * Alur:
     *  1. Validasi data via RegisterRequest (email unik, format foto KTP, dll)
     *  2. Upload foto KTP ke storage jika ada (hanya untuk penyedia)
     *  3. Tentukan status verifikasi berdasarkan peran
     *  4. Buat akun Pengguna baru di database
     *  5. Trigger event Registered (untuk verifikasi email jika diaktifkan)
     *  6. Login otomatis setelah registrasi
     *  7. Redirect ke dashboard sesuai peran
     *
     * @throws ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // Ambil semua data yang sudah divalidasi dari FormRequest
        $data = $request->validated();

        // Upload foto KTP ke private disk (tidak bisa diakses publik via URL).
        // Disimpan di storage/app/private/ktp/ — hanya bisa dibuka admin via controller.
        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            // KTP disimpan di private disk dengan kualitas lebih tinggi agar tetap terbaca jelas
            $fotoKtpPath = $this->simpanGambarTerkompres($request->file('foto_ktp'), 'ktp', 'local', 1600, 85);
        }

        // Penyedia perlu verifikasi admin dulu, pelanggan langsung aktif
        $statusVerifikasi = $data['peran'] === 'penyedia' ? 'pending' : 'diverifikasi';

        // Buat record pengguna baru di database
        $user = Pengguna::create([
            'nama_lengkap'      => $data['nama_lengkap'],
            'email'             => $data['email'],
            'no_telepon'        => $data['no_telepon'],
            'alamat'            => $data['alamat'],
            'peran'             => $data['peran'],
            'status_verifikasi' => $statusVerifikasi,
            'foto_ktp'          => $fotoKtpPath,
            'password'          => Hash::make($data['password']), // Hash password sebelum disimpan
        ]);

        // Trigger event Registered — berguna jika verifikasi email diaktifkan
        event(new Registered($user));

        // Login otomatis setelah registrasi berhasil
        Auth::login($user);

        // Redirect ke dashboard sesuai peran
        // Penyedia yang baru daftar akan melihat halaman 'pending verifikasi'
        return redirect()->route($user->peran . '.dashboard');
    }
}
