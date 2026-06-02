<?php

namespace App\Models;

// Menggunakan interface MustVerifyEmail jika diperlukan verifikasi email
use App\Models\FavoritJasa;
use Illuminate\Contracts\Auth\MustVerifyEmail;
// Trait untuk pembuatan data palsu (factory) saat testing/seeding
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Kelas dasar untuk model yang bisa login (autentikasi)
use Illuminate\Foundation\Auth\User as Authenticatable;
// Trait untuk fitur notifikasi bawaan Laravel
use Illuminate\Notifications\Notifiable;
// Facade untuk operasi file/storage (upload foto)
use Illuminate\Support\Facades\Storage;

/**
 * Model Pengguna
 *
 * Merepresentasikan semua pengguna dalam sistem: admin, penyedia jasa, dan pelanggan.
 * Satu tabel ini digunakan untuk ketiga peran (Single Table Inheritance via kolom `peran`).
 *
 * Kolom penting:
 *  - peran            : 'admin' | 'penyedia' | 'pelanggan'
 *  - status_verifikasi: 'pending' | 'diverifikasi' | 'ditolak' (khusus penyedia)
 *  - is_aktif         : boolean — jika false, akun diblokir oleh admin
 *  - latitude/longitude: koordinat GPS untuk fitur pencarian terdekat
 */
class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel di database (override karena bukan nama default Laravel)
    protected $table = 'pengguna';

    // Primary key khusus (bukan 'id' bawaan Laravel)
    protected $primaryKey = 'id_pengguna';

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     * Kolom yang tidak ada di sini tidak bisa diisi via create() atau update().
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_telepon',
        'alamat',
        'peran',
        'status_verifikasi',
        'is_aktif',
        'foto_ktp',
        'foto_profil',
        'latitude',
        'longitude',
        'email_verified_at',
        'pesan_banding',
    ];

    /**
     * Kolom yang disembunyikan ketika model dikonversi ke array/JSON.
     * Penting: password dan remember_token tidak boleh terekspos ke response API.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom saat dibaca dari database.
     * Memastikan tipe PHP yang tepat: DateTime, float, boolean, hashed string.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',    // Otomatis jadi objek Carbon
            'latitude'          => 'decimal:8',   // Presisi 8 angka desimal
            'longitude'         => 'decimal:8',
            'password'          => 'hashed',      // Otomatis di-hash saat disimpan
            'is_aktif'          => 'boolean',     // Tinyint 0/1 jadi true/false
        ];
    }

    // =====================================================================
    // Relationships — Relasi ke model lain
    // =====================================================================

    /**
     * Jasa yang difavoritkan/disimpan oleh pelanggan ini.
     */
    public function favoritJasa()
    {
        return $this->hasMany(FavoritJasa::class, 'id_pelanggan', 'id_pengguna');
    }

    /**
     * Laporan yang diterima oleh penyedia ini (sebagai pihak yang dilaporkan).
     * Digunakan admin untuk melihat track record laporan terhadap seorang penyedia.
     */
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id_penyedia', 'id_pengguna');
    }

    /**
     * Semua jasa/layanan yang dimiliki oleh pengguna ini sebagai penyedia.
     */
    public function jasaSebagaiPenyedia()
    {
        return $this->hasMany(Jasa::class, 'id_penyedia', 'id_pengguna');
    }

    /**
     * Semua pesanan yang dibuat oleh pengguna ini sebagai pelanggan.
     */
    public function pesananSebagaiPelanggan()
    {
        return $this->hasMany(PesananJasa::class, 'id_pelanggan', 'id_pengguna');
    }

    /**
     * Portofolio karya/proyek milik penyedia ini.
     */
    public function portofolio()
    {
        return $this->hasMany(PortofolioJasa::class, 'id_penyedia', 'id_pengguna');
    }

    // =====================================================================
    // Query Scopes — Filter query yang bisa dipanggil berantai
    // =====================================================================

    /**
     * Scope: hanya penyedia yang sudah diverifikasi oleh admin.
     * Contoh penggunaan: Pengguna::penyedia()->verified()->get()
     */
    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'diverifikasi');
    }

    /**
     * Scope: filter hanya pengguna berperan 'penyedia'.
     */
    public function scopePenyedia($query)
    {
        return $query->where('peran', 'penyedia');
    }

    /**
     * Scope: filter hanya pengguna berperan 'pelanggan'.
     */
    public function scopePelanggan($query)
    {
        return $query->where('peran', 'pelanggan');
    }

    // =====================================================================
    // Accessors — Atribut virtual yang dihitung saat diakses
    // =====================================================================

    /**
     * Menghasilkan URL foto profil pengguna.
     *
     * Prioritas:
     *  1. Foto yang diunggah pengguna (dari storage/public)
     *  2. Avatar inisial dari layanan eksternal ui-avatars.com
     *
     * Diakses via: $pengguna->foto_profil_url
     */
    public function getFotoProfilUrlAttribute(): string
    {
        // Cek apakah foto sudah diunggah dan file-nya ada di storage
        if ($this->foto_profil && Storage::disk('public')->exists($this->foto_profil)) {
            return Storage::disk('public')->url($this->foto_profil);
        }

        // Fallback: ambil inisial dari nama (maks 2 kata), buat avatar berwarna ungu
        $initials = collect(explode(' ', $this->nama_lengkap))
            ->take(2)
            ->map(fn ($w) => strtoupper(substr($w, 0, 1)))
            ->implode('');

        return 'https://ui-avatars.com/api/?name='.urlencode($initials).'&background=6366f1&color=fff&size=128&bold=true';
    }

    /**
     * Menghitung rating rata-rata penyedia berdasarkan semua ulasan yang diterima.
     *
     * Alur kalkulasi:
     *  1. Ambil semua jasa milik penyedia ini
     *  2. Dari setiap jasa, ambil semua pesanan
     *  3. Filter pesanan yang memiliki ulasan
     *  4. Ambil rata-rata nilai rating-nya
     *
     * Diakses via: $penyedia->rating_rata_rata
     */
    public function getRatingRataRataAttribute(): float
    {
        $avg = $this->jasaSebagaiPenyedia()
            ->with('pesanan.ulasan')
            ->get()
            ->flatMap(fn ($jasa) => $jasa->pesanan)
            ->filter(fn ($pesanan) => $pesanan->ulasan !== null)
            ->avg(fn ($pesanan) => $pesanan->ulasan->rating);

        // Jika belum ada ulasan, kembalikan 0
        return round($avg ?? 0, 1);
    }

    // =====================================================================
    // Helpers — Metode utilitas untuk pengecekan peran/status
    // =====================================================================

    /** Cek apakah penyedia ini sudah diverifikasi oleh admin. */
    public function sudahDiverifikasi(): bool
    {
        return $this->status_verifikasi === 'diverifikasi';
    }

    /** Cek apakah pengguna ini berperan sebagai penyedia jasa. */
    public function isPenyedia(): bool
    {
        return $this->peran === 'penyedia';
    }

    /** Cek apakah pengguna ini berperan sebagai pelanggan. */
    public function isPelanggan(): bool
    {
        return $this->peran === 'pelanggan';
    }

    /** Cek apakah pengguna ini berperan sebagai admin sistem. */
    public function isAdmin(): bool
    {
        return $this->peran === 'admin';
    }
}
