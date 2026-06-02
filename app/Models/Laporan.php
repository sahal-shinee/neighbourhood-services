<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Laporan
 *
 * Merepresentasikan laporan pelanggaran yang diajukan pelanggan terhadap penyedia jasa.
 * Diproses oleh admin untuk memutuskan tindakan (termasuk menonaktifkan akun penyedia).
 *
 * Alur status laporan:
 *   'baru' → 'ditinjau' → 'ditindaklanjuti'  (admin mengambil tindakan)
 *   'baru' → 'ditinjau' → 'ditolak'          (laporan tidak terbukti)
 *
 * Laporan dengan status 'ditindaklanjuti' atau 'ditolak' akan otomatis dihapus
 * oleh sistem setelah 30 hari (via command: php artisan laporan:purge).
 */
class Laporan extends Model
{
    // Nama tabel di database
    protected $table = 'laporan';

    // Primary key khusus
    protected $primaryKey = 'id_laporan';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_pelapor',      // Pelanggan yang mengajukan laporan
        'id_penyedia',     // Penyedia yang dilaporkan
        'id_pesanan',      // Pesanan terkait sebagai konteks (opsional)
        'alasan',          // Kategori pelanggaran (max 100 karakter, pilihan preset)
        'detail_laporan',  // Uraian lengkap kronologi pelanggaran
        'bukti_foto',      // Path file foto bukti (opsional, di storage/public)
        'status',          // Enum: baru|ditinjau|ditindaklanjuti|ditolak
        'catatan_admin',   // Keterangan tindakan yang diambil admin (opsional)
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * Pelanggan yang mengajukan laporan (pelapor).
     */
    public function pelapor()
    {
        return $this->belongsTo(Pengguna::class, 'id_pelapor', 'id_pengguna');
    }

    /**
     * Penyedia jasa yang dilaporkan.
     */
    public function penyedia()
    {
        return $this->belongsTo(Pengguna::class, 'id_penyedia', 'id_pengguna');
    }

    /**
     * Pesanan yang dijadikan konteks/referensi laporan (nullable).
     */
    public function pesanan()
    {
        return $this->belongsTo(PesananJasa::class, 'id_pesanan', 'id_pesanan');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Label status laporan dalam Bahasa Indonesia untuk ditampilkan di UI.
     * Digunakan di badge status pada tabel laporan admin dan riwayat laporan pelanggan.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'baru'            => 'Baru',
            'ditinjau'        => 'Ditinjau',
            'ditindaklanjuti' => 'Ditindaklanjuti',
            'ditolak'         => 'Ditolak',
            default           => ucfirst($this->status),
        };
    }

    /**
     * Kelas Tailwind CSS untuk warna badge status laporan.
     * Nilai dikonsumsi langsung di template Blade: class="{{ $laporan->statusColor() }}"
     *
     * Warna:
     *  - Baru            : biru  (belum diproses)
     *  - Ditinjau        : amber (sedang diproses admin)
     *  - Ditindaklanjuti : hijau (selesai dengan tindakan)
     *  - Ditolak         : merah (ditolak/tidak terbukti)
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            'baru'            => 'bg-blue-50 text-blue-700 border-blue-100',
            'ditinjau'        => 'bg-amber-50 text-amber-700 border-amber-100',
            'ditindaklanjuti' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'ditolak'         => 'bg-red-50 text-red-700 border-red-100',
            default           => 'bg-gray-50 text-gray-600 border-gray-100',
        };
    }
}
