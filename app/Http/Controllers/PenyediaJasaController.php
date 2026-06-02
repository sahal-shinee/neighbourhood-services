<?php

namespace App\Http\Controllers;

use App\Http\Requests\JasaRequest;
use App\Models\Jasa;
use App\Models\Kategori;
use App\Traits\CompressesImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller PenyediaJasaController
 *
 * Mengelola CRUD jasa/layanan yang dimiliki penyedia.
 *
 * Fitur khusus:
 *  - Mendukung sistem tarif fleksibel: per_jam, per_pengerjaan, atau paket
 *  - Untuk tipe 'paket': bisa memiliki hingga 5 tingkatan harga (PaketHarga)
 *  - Toggle aktif/nonaktif jasa dengan proteksi: tidak bisa dinonaktifkan
 *    jika masih ada pesanan aktif (menunggu/disetujui)
 *  - Hapus jasa hanya jika belum pernah ada transaksi
 */
class PenyediaJasaController extends Controller
{
    use CompressesImages;
    /**
     * Daftar semua jasa milik penyedia yang sedang login.
     * Include relasi 'paket' untuk menampilkan info harga di kartu jasa.
     */
    public function index(Request $request)
    {
        $jasa = $request->user()->jasaSebagaiPenyedia()->with('paket')->latest()->paginate(10);
        return view('penyedia.jasa.index', compact('jasa'));
    }

    /**
     * Tampilkan form pembuatan jasa baru.
     * Daftar kategori dikirim untuk mengisi dropdown pilihan kategori.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('penyedia.jasa.create', compact('kategori'));
    }

    /**
     * Simpan jasa baru ke database.
     *
     * Alur untuk tipe tarif 'paket':
     *  1. Pisahkan data paket dari data jasa utama
     *  2. Simpan data jasa
     *  3. Filter paket yang valid (nama dan harga terisi)
     *  4. Simpan setiap paket dengan nomor urut
     */
    public function store(JasaRequest $request)
    {
        $data    = $request->validated();
        // Pisahkan array paket dari data jasa — akan disimpan terpisah ke tabel paket_harga
        $paketIn = $data['paket'] ?? [];
        $data    = collect($data)->except('paket')->toArray();

        // Tambahkan ID penyedia dari pengguna yang login
        $data['id_penyedia'] = $request->user()->id_pengguna;
        $data['is_aktif']    = $request->boolean('is_aktif', true);

        // Upload foto jasa jika ada file yang dikirim
        if ($request->hasFile('foto_jasa')) {
            $data['foto_jasa'] = $this->simpanGambarTerkompres($request->file('foto_jasa'), 'jasa');
        }

        // Simpan data jasa utama ke database
        $jasa = Jasa::create($data);

        // Simpan data paket harga jika tipe tarif adalah 'paket'
        if ($data['tipe_tarif'] === 'paket') {
            // Filter hanya paket yang memiliki nama dan harga terisi (abaikan baris kosong)
            $validPaket = collect($paketIn)
                ->filter(fn($p) => filled($p['nama_paket'] ?? null) && filled($p['harga'] ?? null))
                ->values();

            // Simpan setiap paket dengan nomor urut (1, 2, 3...)
            foreach ($validPaket as $i => $p) {
                $jasa->paket()->create([
                    'nama_paket' => $p['nama_paket'],
                    'harga'      => $p['harga'],
                    'deskripsi'  => $p['deskripsi'] ?? null,
                    'urutan'     => $i + 1,
                ]);
            }
        }

        return redirect()->route('penyedia.jasa.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function show($id)
    {
        // Tidak diimplementasikan — detail jasa dilihat via halaman publik
    }

    /**
     * Tampilkan form edit jasa.
     * Memastikan penyedia hanya bisa mengedit jasanya sendiri (where id_penyedia).
     */
    public function edit(Request $request, $id)
    {
        $jasa     = Jasa::with('paket')->where('id_penyedia', $request->user()->id_pengguna)->findOrFail($id);
        $kategori = Kategori::all();
        return view('penyedia.jasa.edit', compact('jasa', 'kategori'));
    }

    /**
     * Simpan perubahan data jasa yang sudah ada.
     *
     * Strategi sinkronisasi paket (sync pattern):
     *  - Hapus semua paket lama
     *  - Buat ulang dari data yang dikirim form
     *  Ini lebih sederhana daripada membandingkan satu per satu.
     */
    public function update(JasaRequest $request, $id)
    {
        $jasa    = Jasa::where('id_penyedia', $request->user()->id_pengguna)->findOrFail($id);
        $data    = $request->validated();
        $paketIn = $data['paket'] ?? [];
        $data    = collect($data)->except('paket')->toArray();

        // Checkbox is_aktif: jika tidak dicentang, tidak terkirim → anggap false
        $data['is_aktif'] = $request->boolean('is_aktif', false);

        // Update foto jasa jika ada file baru yang dikirim
        if ($request->hasFile('foto_jasa')) {
            // Hapus foto lama sebelum simpan yang baru
            if ($jasa->foto_jasa) {
                Storage::disk('public')->delete($jasa->foto_jasa);
            }
            $data['foto_jasa'] = $this->simpanGambarTerkompres($request->file('foto_jasa'), 'jasa');
        }

        $jasa->update($data);

        // Sinkronisasi paket: hapus semua lama, buat ulang dari input baru
        $jasa->paket()->delete();
        if ($data['tipe_tarif'] === 'paket') {
            $validPaket = collect($paketIn)
                ->filter(fn($p) => filled($p['nama_paket'] ?? null) && filled($p['harga'] ?? null))
                ->values();

            foreach ($validPaket as $i => $p) {
                $jasa->paket()->create([
                    'nama_paket' => $p['nama_paket'],
                    'harga'      => $p['harga'],
                    'deskripsi'  => $p['deskripsi'] ?? null,
                    'urutan'     => $i + 1,
                ]);
            }
        }

        return redirect()->route('penyedia.jasa.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif jasa.
     *
     * Proteksi penting:
     *  Jika ingin menonaktifkan, cek dulu apakah ada pesanan aktif.
     *  Pesanan dengan status 'menunggu' atau 'disetujui' harus diselesaikan dulu.
     */
    public function toggleStatus(Request $request, $id)
    {
        $jasa      = Jasa::where('id_penyedia', $request->user()->id_pengguna)->findOrFail($id);
        $newStatus = !$jasa->is_aktif;

        // Jika akan dinonaktifkan, cek pesanan aktif yang belum selesai
        if (!$newStatus) {
            $activeOrders = $jasa->pesanan()
                ->whereIn('status_pesanan', ['menunggu', 'disetujui'])
                ->count();

            if ($activeOrders > 0) {
                return back()->with('error', "Layanan tidak dapat dinonaktifkan. Masih terdapat {$activeOrders} pesanan aktif.");
            }
        }

        $jasa->update(['is_aktif' => $newStatus]);

        $statusMsg = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Layanan berhasil {$statusMsg}.");
    }

    /**
     * Hapus jasa secara permanen beserta foto dan semua paket harganya.
     *
     * Proteksi: jasa yang sudah memiliki riwayat transaksi tidak bisa dihapus
     * (untuk menjaga integritas riwayat pesanan pelanggan).
     * Solusi alternatif yang disarankan: nonaktifkan saja.
     */
    public function destroy(Request $request, $id)
    {
        $jasa = Jasa::where('id_penyedia', $request->user()->id_pengguna)->findOrFail($id);

        // Cegah penghapusan jika sudah ada riwayat pesanan
        $totalOrders = $jasa->pesanan()->count();
        if ($totalOrders > 0) {
            return back()->with('error', "Layanan tidak dapat dihapus karena sudah memiliki {$totalOrders} riwayat transaksi/pesanan. Silakan nonaktifkan layanan jika tidak ingin menawarkannya lagi.");
        }

        // Hapus semua paket harga terkait terlebih dahulu
        $jasa->paket()->delete();

        // Hapus foto jasa dari storage
        if ($jasa->foto_jasa) {
            Storage::disk('public')->delete($jasa->foto_jasa);
        }

        $jasa->delete();

        return back()->with('success', 'Layanan berhasil dihapus secara permanen.');
    }
}
