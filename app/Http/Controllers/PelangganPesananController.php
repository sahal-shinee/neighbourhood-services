<?php

namespace App\Http\Controllers;

use App\Models\PesananJasa;
use Illuminate\Http\Request;

/**
 * Controller PelangganPesananController
 *
 * Mengelola aksi pelanggan terhadap pesanan yang sudah dibuat:
 *  - Melihat daftar riwayat pesanan
 *  - Melihat detail satu pesanan
 *  - Membatalkan pesanan yang masih menunggu
 *  - Memperbarui estimasi hari (untuk tipe paket)
 *  - Menghapus riwayat pesanan yang sudah selesai/dibatalkan
 */
class PelangganPesananController extends Controller
{
    /**
     * Daftar semua pesanan milik pelanggan yang sedang login.
     * Mendukung filter berdasarkan status pesanan.
     */
    public function index(Request $request)
    {
        // Mulai dari relasi pesanan milik pelanggan yang login untuk keamanan data
        $query = $request->user()->pesananSebagaiPelanggan()->with('jasa.penyedia');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        // Filter tanggal: dari tanggal tertentu
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_booking', '>=', $request->dari);
        }

        // Filter tanggal: sampai tanggal tertentu
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_booking', '<=', $request->sampai);
        }

        $pesanan = $query->latest()->paginate(10)->withQueryString();
        return view('pelanggan.pesanan.index', compact('pesanan'));
    }

    /**
     * Tampilkan detail satu pesanan termasuk data jasa, penyedia, dan ulasan (jika ada).
     * Memverifikasi kepemilikan: hanya pesanan milik pelanggan sendiri yang bisa dilihat.
     */
    public function show(Request $request, $id)
    {
        $pesanan = $request->user()->pesananSebagaiPelanggan()
            ->with(['jasa.penyedia', 'ulasan'])
            ->findOrFail($id);
        return view('pelanggan.pesanan.show', compact('pesanan'));
    }

    /**
     * Batalkan pesanan oleh pelanggan.
     *
     * Batasan: hanya pesanan berstatus 'menunggu' yang bisa dibatalkan.
     * Pesanan yang sudah 'disetujui' atau 'selesai' tidak bisa dibatalkan pelanggan.
     * Setelah dibatalkan, penyedia mendapat notifikasi.
     */
    public function batal(Request $request, $id)
    {
        $pesanan = $request->user()->pesananSebagaiPelanggan()->findOrFail($id);

        // Cegah pembatalan jika pesanan sudah melewati status 'menunggu'
        if ($pesanan->status_pesanan !== 'menunggu') {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan karena sudah ' . $pesanan->status_label . '.');
        }

        $pesanan->update(['status_pesanan' => 'dibatalkan']);

        // Beritahu penyedia bahwa pelanggan membatalkan pesanan
        $pesanan->jasa->penyedia->notify(new \App\Notifications\PesananDibatalkanNotification($pesanan));

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Perbarui estimasi hari pengerjaan untuk pesanan bertipe paket.
     *
     * Fitur ini memungkinkan pelanggan dan penyedia menyepakati durasi pengerjaan
     * setelah pesanan dibuat (misalnya setelah diskusi lebih lanjut).
     *
     * Pembatasan:
     *  - Hanya untuk tipe tarif 'paket'
     *  - Hanya saat pesanan masih aktif (menunggu atau disetujui)
     */
    public function updateEstimasi(Request $request, $id)
    {
        $pesanan = $request->user()->pesananSebagaiPelanggan()->findOrFail($id);

        if ($pesanan->jasa->tipe_tarif !== 'paket') {
            return back()->with('error', 'Estimasi hanya berlaku untuk pesanan bertipe Paket.');
        }

        if (!in_array($pesanan->status_pesanan, ['menunggu', 'disetujui'])) {
            return back()->with('error', 'Pesanan sudah selesai atau dibatalkan, estimasi tidak dapat diubah.');
        }

        $request->validate([
            'estimasi_hari' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $pesanan->update(['estimasi_hari' => $request->estimasi_hari]);

        return back()->with('success', 'Estimasi hari pengerjaan berhasil diperbarui.');
    }

    /**
     * Hapus riwayat pesanan dari tampilan pelanggan.
     *
     * Batasan keamanan: hanya pesanan yang sudah terminal (selesai atau dibatalkan)
     * yang boleh dihapus. Pesanan aktif tidak bisa dihapus langsung —
     * harus dibatalkan dulu melalui tombol "Batalkan".
     */
    public function destroy(Request $request, $id)
    {
        $pesanan = $request->user()->pesananSebagaiPelanggan()->findOrFail($id);

        // Pastikan pesanan sudah tidak aktif sebelum dihapus
        if (!in_array($pesanan->status_pesanan, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Pesanan aktif tidak dapat dihapus. Batalkan pesanan terlebih dahulu.');
        }

        $pesanan->delete();
        return redirect()->route('pelanggan.pesanan.index')->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}
