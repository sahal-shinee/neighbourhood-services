<!-- HEADER BANNER -->
<img src="https://capsule-render.vercel.app/api?type=waving&color=0:4f46e5,50:7c3aed,100:2563eb&height=220&section=header&text=Neighbourhood%20Services&fontSize=48&fontColor=ffffff&animation=fadeIn&fontAlignY=40&desc=Platform%20Marketplace%20Jasa%20Berbasis%20Lokasi%20%7C%20Laravel%2012&descAlignY=62&descSize=16&descColor=c7d2fe" width="100%"/>

<div align="center">

<!-- STATUS BADGES -->
<p>
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/Tailwind_CSS-v3-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white"/>
  <img src="https://img.shields.io/badge/Alpine.js-v3-77C1D2?style=for-the-badge&logo=alpine.js&logoColor=black"/>
</p>
<p>
  <img src="https://img.shields.io/badge/Status-Production%20Ready-22c55e?style=flat-square&logo=checkmarx&logoColor=white"/>
  <img src="https://img.shields.io/badge/License-MIT-blue?style=flat-square"/>
  <img src="https://img.shields.io/badge/Version-1.0.0-purple?style=flat-square"/>
  <img src="https://img.shields.io/badge/Maintained-Yes-brightgreen?style=flat-square"/>
</p>

<br/>

> **Neighbourhood Services** menghubungkan warga dengan penyedia jasa terpercaya di sekitar mereka —
> mulai dari kebersihan rumah, reparasi elektronik, les privat, hingga perawatan kendaraan.
> Semua dalam satu platform yang aman, terverifikasi, dan mudah digunakan.

<br/>

**[🚀 Mulai Instalasi](#-instalasi)** &nbsp;·&nbsp;
**[✨ Lihat Fitur](#-fitur)** &nbsp;·&nbsp;
**[🔒 Keamanan](#-keamanan)** &nbsp;·&nbsp;
**[🌐 Deploy](#-panduan-deploy)**

</div>

<br/>

---

## 📑 Daftar Isi

<details open>
<summary><b>Klik untuk expand/collapse</b></summary>

- [✨ Fitur](#-fitur)
- [🛠️ Tech Stack](#-tech-stack)
- [📦 Persyaratan Sistem](#-persyaratan-sistem)
- [🚀 Instalasi](#-instalasi)
- [⚙️ Konfigurasi Environment](#-konfigurasi-environment)
- [👥 Struktur Peran & Alur Sistem](#-struktur-peran--alur-sistem)
- [🔧 Perintah Artisan](#-perintah-artisan)
- [⏰ Tugas Terjadwal](#-tugas-terjadwal)
- [📁 Struktur Direktori](#-struktur-direktori)
- [🌐 Panduan Deploy](#-panduan-deploy)
- [🔒 Keamanan](#-keamanan)
- [📸 Tampilan Sistem](#-tampilan-sistem)
- [🤝 Kontribusi](#-kontribusi)

</details>

---

## ✨ Fitur

<table>
<tr>
<td width="33%" valign="top">

### 👤 Pelanggan
- 🔍 Pencarian jasa dengan filter kategori & kata kunci
- 📍 Deteksi GPS opsional — urutkan berdasarkan jarak
- 💰 Urutkan termurah/termahal/terbaru/terjauh
- 📅 Booking dengan 3 sistem tarif (jam/pengerjaan/paket)
- ❤️ Simpan jasa favorit
- 📦 Riwayat pesanan + filter tanggal
- ⭐ Ulasan & rating setelah selesai
- 🚩 Laporkan penyedia + upload bukti foto
- 📜 Pantau status laporan secara transparan
- 🔔 Notifikasi real-time setiap perubahan status
- 🔑 Ganti password langsung dari profil

</td>
<td width="33%" valign="top">

### 🔧 Penyedia Jasa
- 📋 Kelola layanan (tambah/edit/hapus)
- 📦 Sistem paket harga bertingkat (maks 5 tingkat)
- 📥 Terima/tolak/selesaikan pesanan masuk
- ⚡ Auto-cancel pesanan konflik saat menyetujui
- 🗓️ Kalender jadwal interaktif (FullCalendar)
- 🖼️ Portofolio karya untuk menarik pelanggan
- 📊 Dashboard kinerja + estimasi pendapatan
- 🔄 Ajukan banding jika verifikasi ditolak
- 💬 Kontak langsung via WhatsApp dalam-app

</td>
<td width="33%" valign="top">

### 🛡️ Admin
- ✅ Verifikasi identitas penyedia (KTP viewer aman)
- 🚫 Nonaktifkan akun + batalkan pesanan aktif otomatis
- 📊 Dashboard analitik dengan grafik tren pesanan
- 👥 Kelola semua pengguna platform
- 🏷️ Kelola kategori jasa
- 📋 Tinjau & tindaklanjuti laporan pelanggaran
- 🗑️ Auto-purge laporan & notifikasi lama
- 📈 Grafik distribusi peran pengguna (donut chart)

</td>
</tr>
</table>

### 🔐 Fitur Keamanan & Sistem

<table>
<tr>
<td><code>Rate Limiting</code></td><td>Login & registrasi dibatasi 5x/menit per IP</td>
<td><code>No-Cache Headers</code></td><td>Mencegah akses dashboard via Back button setelah logout</td>
</tr>
<tr>
<td><code>KTP Private Storage</code></td><td>Foto KTP tersimpan di private disk, hanya admin yang bisa akses</td>
<td><code>SQL Injection Guard</code></td><td>Koordinat GPS di-cast ke float sebelum dimasukkan raw SQL</td>
</tr>
<tr>
<td><code>Session Deactivation</code></td><td>Akun yang diblokir langsung dikeluarkan di request berikutnya</td>
<td><code>Image Compression</code></td><td>Semua upload otomatis di-resize & dikompres (Intervention Image)</td>
</tr>
</table>

---

## 🛠️ Tech Stack

<div align="center">

<img src="https://skillicons.dev/icons?i=laravel,php,mysql,tailwind,alpinejs,git&theme=light" />

</div>

<br/>

<div align="center">

| Layer | Teknologi | Keterangan |
|:---:|:---:|---|
| **Backend** | Laravel 12 + PHP 8.2 | Framework utama, routing, ORM, middleware |
| **Styling** | Tailwind CSS v3 (CDN JIT) | Utility-first CSS, responsive design |
| **Interaktivitas** | Alpine.js v3 | Reaktivitas ringan tanpa build step |
| **Kalender** | FullCalendar v5 | Visualisasi jadwal interaktif |
| **Image** | Intervention Image v3 | Resize + kompres foto upload |
| **Database** | MySQL 8 / MariaDB 10.4 | Relational database dengan optimasi index |
| **Notifikasi** | Laravel DB Notifications | Penyimpanan lokal, tidak butuh queue worker |
| **Scheduler** | Laravel Task Scheduling | Otomasi purge data lama setiap hari |

</div>

---

## 📦 Persyaratan Sistem

<div align="center">

| Kebutuhan | Versi Minimum | Catatan |
|:---:|:---:|---|
| PHP | `>= 8.2` | Dengan ekstensi: `pdo`, `mbstring`, `gd`, `openssl`, `xml` |
| Composer | `>= 2.x` | Dependency manager PHP |
| MySQL | `>= 8.0` | Atau MariaDB >= 10.4 |
| Web Server | Apache / Nginx | Dengan `mod_rewrite` aktif |

</div>

---

## 🚀 Instalasi

<details open>
<summary><b>Panduan instalasi lengkap (klik untuk expand)</b></summary>

### Langkah 1 — Clone Repositori

```bash
git clone https://github.com/username/neighbourhood-services.git
cd neighbourhood-services
```

### Langkah 2 — Install PHP Dependencies

```bash
composer install
```

### Langkah 3 — Setup Environment

```bash
# Salin file konfigurasi contoh
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Langkah 4 — Konfigurasi Database

Buka `.env` dan sesuaikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=neighbourhood_services
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Langkah 5 — Migrasi Database

```bash
# Jalankan semua migrasi (buat tabel)
php artisan migrate

# Opsional: isi data awal (kategori + akun admin)
php artisan db:seed
```

### Langkah 6 — Buat Storage Link

```bash
php artisan storage:link
```

> Perintah ini membuat symbolic link dari `public/storage` → `storage/app/public` sehingga file upload bisa diakses via URL.

### Langkah 7 — Jalankan Server

```bash
php artisan serve
```

🎉 **Buka browser:** `http://127.0.0.1:8000`

</details>

---

## ⚙️ Konfigurasi Environment

<details>
<summary><b>Lihat konfigurasi .env lengkap</b></summary>

```env
# ╔══════════════════════════════════════════════╗
# ║            KONFIGURASI APLIKASI              ║
# ╚══════════════════════════════════════════════╝
APP_NAME="Neighbourhood Services"
APP_ENV=local               # Ubah ke 'production' saat deploy
APP_KEY=                    # Diisi otomatis via php artisan key:generate
APP_DEBUG=true              # ⚠️ WAJIB false di production
APP_URL=http://localhost

# ╔══════════════════════════════════════════════╗
# ║               DATABASE                       ║
# ╚══════════════════════════════════════════════╝
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=neighbourhood_services
DB_USERNAME=root
DB_PASSWORD=

# ╔══════════════════════════════════════════════╗
# ║                  EMAIL                       ║
# ╚══════════════════════════════════════════════╝
# Development : gunakan 'log' → cek di storage/logs/laravel.log
# Production  : ganti ke 'smtp' dengan kredensial asli

MAIL_MAILER=log
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# ╔══════════════════════════════════════════════╗
# ║           SESSION & CACHE & QUEUE            ║
# ╚══════════════════════════════════════════════╝
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync       # Notifikasi diproses langsung (tanpa worker)
FILESYSTEM_DISK=local
```

> 💡 **Tip Development:** Untuk melihat link reset password, jalankan:
> ```bash
> tail -f storage/logs/laravel.log | grep "reset"
> ```

</details>

---

## 👥 Struktur Peran & Alur Sistem

### Akun Default

<div align="center">

| Peran | Email | Password | Akses |
|:---:|:---:|:---:|---|
| 🛡️ Admin | `admin@neighbourhood.com` | `password` | Panel admin penuh |
| 🔧 Penyedia | Daftar mandiri | — | Setelah diverifikasi admin |
| 👤 Pelanggan | Daftar mandiri | — | Langsung aktif |

</div>

### Alur Verifikasi Penyedia

```
  Daftar Penyedia (upload KTP)
           │
           ▼
    ┌─────────────┐
    │   PENDING   │  ← Menunggu review admin
    └──────┬──────┘
           │
     ┌─────┴──────┐
     ▼            ▼
┌──────────┐ ┌─────────┐
│DIVERIFI- │ │ DITOLAK │
│  KASI    │ └────┬────┘
└────┬─────┘      │
     │        Ajukan Banding
     ▼             │
Bisa listing   ┌───▼────┐
   jasa        │ PENDING│ ← Kembali ke antrian review
               └────────┘
```

### Alur Pesanan

```
  Pelanggan booking
         │
         ▼
  ┌────────────┐
  │  MENUNGGU  │ ← Penyedia belum merespons
  └──────┬─────┘
         │
   ┌─────┴──────────┐
   ▼                ▼
┌──────────┐  ┌──────────────┐
│DISETUJUI │  │ DIBATALKAN   │ ← Pelanggan batal / Penyedia tolak
└────┬─────┘  └──────────────┘
     │   ↑
     │   └─ Pesanan bentrok
     │      otomatis dibatalkan
     ▼
┌──────────┐
│ SELESAI  │ ← Penyedia tandai selesai
└────┬─────┘
     │
     ▼
  Pelanggan
  beri ULASAN ⭐
```

---

## 🔧 Perintah Artisan

```bash
# ── Development ───────────────────────────────────────
php artisan serve                    # Jalankan server lokal
php artisan optimize:clear           # Bersihkan semua cache
php artisan tinker                   # REPL interaktif Laravel

# ── Production ────────────────────────────────────────
php artisan optimize                 # Cache config+route+view sekaligus
php artisan migrate --force          # Migrasi di production
php artisan storage:link             # Buat symlink public storage

# ── Laporan & Maintenance ─────────────────────────────
php artisan laporan:purge            # Hapus laporan selesai/ditolak > 30 hari
php artisan laporan:purge --dry-run  # Preview tanpa menghapus
php artisan laporan:purge --days=7   # Custom threshold (default: 30 hari)
php artisan schedule:list            # Lihat semua jadwal task aktif
php artisan schedule:run             # Jalankan scheduler secara manual
```

---

## ⏰ Tugas Terjadwal

Dua tugas otomatis berjalan setiap hari untuk menjaga database tetap bersih:

<div align="center">

| 🕐 Waktu | 📋 Perintah | 📝 Fungsi |
|:---:|:---:|---|
| `03:00` setiap hari | `laporan:purge` | Hapus laporan **ditindaklanjuti/ditolak** yang berumur > 30 hari beserta file bukti foto |
| `03:30` setiap hari | `purge-old-notifications` | Hapus notifikasi yang sudah **dibaca** dan berumur > 30 hari |

</div>

**Setup Cron di Server Linux:**

```bash
crontab -e
# Tambahkan baris berikut:
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

**Setup di cPanel (Shared Hosting):**

Masuk ke **cPanel → Cron Jobs**, tambahkan:
```
* * * * * /usr/local/bin/php /home/username/neighbourhood_services/artisan schedule:run
```

---

## 📁 Struktur Direktori

<details>
<summary><b>Lihat struktur direktori lengkap</b></summary>

```
neighbourhood-services/
│
├── 📂 app/
│   ├── 📂 Console/Commands/
│   │   └── PurgeLaporan.php              # Artisan command hapus laporan lama
│   │
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/
│   │   │   ├── AdminController.php        # Dashboard, verifikasi, pesanan admin
│   │   │   ├── AdminLaporanController.php # Kelola laporan & nonaktifkan penyedia
│   │   │   ├── AdminKategoriController.php
│   │   │   ├── AdminPenggunaController.php
│   │   │   ├── PelangganController.php    # Dashboard, cari jasa, profil
│   │   │   ├── PelangganBookingController.php
│   │   │   ├── PelangganPesananController.php
│   │   │   ├── PelangganFavoritController.php # Favorit jasa (toggle & index)
│   │   │   ├── LaporanController.php      # Submit & riwayat laporan pelanggan
│   │   │   ├── PenyediaController.php     # Dashboard, profil, banding
│   │   │   ├── PenyediaJasaController.php # CRUD jasa + paket harga
│   │   │   ├── PenyediaPesananController.php
│   │   │   ├── BookingApiController.php   # JSON endpoint jadwal tersedia
│   │   │   ├── NotifikasiController.php
│   │   │   └── StaticPageController.php   # T&C dan Kebijakan Privasi
│   │   │
│   │   ├── 📂 Middleware/
│   │   │   ├── CheckRole.php             # RBAC + deteksi akun dinonaktifkan
│   │   │   └── PreventBackHistory.php    # Header no-cache anti Back button
│   │   │
│   │   └── 📂 Requests/                  # Form Request Validation
│   │       ├── BookingRequest.php         # Validasi booking + max 12 jam
│   │       ├── JasaRequest.php            # Validasi jasa + paket harga
│   │       ├── RegisterRequest.php
│   │       ├── ProfilRequest.php
│   │       └── UlasanRequest.php
│   │
│   ├── 📂 Models/
│   │   ├── Pengguna.php                  # Model utama (admin/penyedia/pelanggan)
│   │   ├── Jasa.php                      # Layanan + tarif_label accessor
│   │   ├── PesananJasa.php               # Transaksi + status state machine
│   │   ├── PaketHarga.php                # Paket harga bertingkat
│   │   ├── Laporan.php                   # Laporan pelanggaran
│   │   ├── FavoritJasa.php               # Jasa favorit pelanggan
│   │   ├── UlasanJasa.php                # Review & rating
│   │   ├── PortofolioJasa.php
│   │   └── Kategori.php
│   │
│   ├── 📂 Notifications/                 # 6 jenis notifikasi database
│   │   ├── PesananBaruNotification.php   # → Penyedia
│   │   ├── PesananDisetujuiNotification.php # → Pelanggan
│   │   ├── PesananSelesaiNotification.php   # → Pelanggan
│   │   ├── PesananDitolakNotification.php   # → Pelanggan
│   │   ├── PesananDibatalkanNotification.php # → Penyedia
│   │   └── LaporanDihapusNotification.php   # → Pelanggan
│   │
│   └── 📂 Traits/
│       └── CompressesImages.php          # Resize + kompres semua upload
│
├── 📂 database/migrations/               # 20+ migrasi database
│
├── 📂 resources/views/
│   ├── 📂 admin/                         # Panel admin (dashboard, verifikasi, dll)
│   ├── 📂 pelanggan/                     # Panel pelanggan
│   │   ├── 📂 favorit/                   # Halaman jasa favorit
│   │   ├── 📂 laporan/                   # Form + riwayat laporan
│   │   ├── 📂 pesanan/                   # Riwayat & detail pesanan
│   │   └── 📂 booking/                   # Form booking
│   ├── 📂 penyedia/                      # Panel penyedia
│   ├── 📂 auth/                          # Login, Register, Reset Password
│   ├── 📂 components/                    # Komponen reusable
│   ├── 📂 errors/                        # Error page 403, 404, 500
│   ├── 📂 static/                        # T&C dan Kebijakan Privasi
│   └── 📂 layouts/                       # app, guest, landing
│
├── 📂 routes/
│   ├── web.php                           # Semua route (terstruktur per peran)
│   ├── auth.php                          # Route autentikasi + throttle
│   └── console.php                       # Scheduled tasks
│
└── 📂 storage/app/
    ├── 📂 public/                        # File yang bisa diakses publik
    │   ├── jasa/                         # Foto layanan
    │   ├── profil/                       # Foto profil
    │   ├── portofolio/                   # Foto portofolio
    │   └── laporan/                      # Bukti foto laporan
    └── 📂 private/                       # File TIDAK bisa diakses publik
        └── ktp/                          # ⚠️ Foto KTP — hanya via controller admin
```

</details>

---

## 🌐 Panduan Deploy

<details>
<summary><b>Checklist deploy ke production</b></summary>

### ✅ Checklist Wajib

```diff
+ APP_DEBUG=false          # Sembunyikan error dari publik
+ APP_ENV=production       # Mode production
+ APP_KEY terisi           # Wajib ada untuk enkripsi
+ MAIL_MAILER=smtp         # Ganti dari 'log' ke SMTP asli
+ Database credentials     # Sesuaikan dengan server production
```

### 📋 Perintah Server

```bash
# Install dependency (tanpa package development)
composer install --optimize-autoloader --no-dev

# Jalankan migrasi
php artisan migrate --force

# Buat symlink storage
php artisan storage:link

# Cache semua untuk performa optimal
php artisan optimize

# Set permission folder
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

</details>

<details>
<summary><b>Setup Shared Hosting (cPanel) — Konfigurasi Folder</b></summary>

> ⚠️ **PERINGATAN KEAMANAN:** Jangan upload semua file ke `public_html/` karena akan mengekspos file `.env` (berisi password database) ke publik!

**Struktur yang BENAR:**

```
/home/cpanelusername/
├── neighbourhood_services/    ← Upload SEMUA file project di sini
│   ├── app/
│   ├── .env                   ← Aman, tidak dapat diakses via URL
│   ├── storage/
│   └── public/                ◄── Domain harus mengarah ke sini
└── public_html/               ← Biarkan kosong / domain lain
```

**Di cPanel → Domains → ubah Document Root ke:**
```
/home/cpanelusername/neighbourhood_services/public
```

</details>

---

## 🔒 Keamanan

<div align="center">

| Ancaman | Perlindungan yang Diimplementasikan |
|:---:|---|
| 🔐 **Brute Force** | Rate limit login & registrasi: 5x/menit per IP |
| ↩️ **Back Button Attack** | `Cache-Control: no-store` pada semua response terautentikasi |
| 💉 **SQL Injection** | Koordinat GPS di-cast ke `(float)` sebelum dimasukkan raw SQL Haversine |
| 📄 **KTP Leakage** | Disimpan di `storage/app/private/` — hanya admin via controller yang bisa akses |
| 🔗 **Session Fixation** | Session di-regenerate setelah login berhasil |
| 🛡️ **CSRF** | Semua form menggunakan `@csrf` token Laravel |
| 🔒 **Mass Assignment** | Semua model menggunakan `$fillable` eksplisit |
| 👻 **Akun Zombie** | Akun yang diblokir langsung di-logout di middleware |

</div>

---

## 📸 Tampilan Sistem

<div align="center">

| Halaman | Peran | Fitur Utama |
|:---:|:---:|---|
| 🏠 **Landing Page** | Publik | Hero section, kategori populer, statistik platform |
| 🔑 **Login / Register** | Publik | Toggle password eye, remember me, rate limiting |
| 📊 **Dashboard Admin** | Admin | Grafik tren pesanan + distribusi pengguna (Chart.js) |
| ✅ **Verifikasi KTP** | Admin | Preview foto KTP dalam modal, approve/reject |
| 🔍 **Cari Jasa** | Pelanggan | Filter GPS, kategori, harga — cards dengan tombol favorit |
| 📅 **Form Booking** | Pelanggan | Pilih jam/paket dinamis + cek jadwal tersedia real-time |
| 🗓️ **Kalender Jadwal** | Penyedia | FullCalendar + stat cards + modal detail klik event |
| 📥 **Pesanan Masuk** | Penyedia | Filter tanggal + status tabs berwarna + pagination |

> 📂 Untuk menambahkan screenshot, simpan gambar di folder `/docs/screenshots/` lalu referensikan di sini.

</div>

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Berikut cara berkontribusi:

```bash
# 1. Fork repositori ini
# 2. Buat branch fitur baru
git checkout -b feature/NamaFiturBaru

# 3. Commit perubahan dengan pesan yang deskriptif
git commit -m "feat: Tambahkan NamaFiturBaru"

# 4. Push ke branch
git push origin feature/NamaFiturBaru

# 5. Buka Pull Request di GitHub
```

**Konvensi commit message:**

| Prefix | Penggunaan |
|:---:|---|
| `feat:` | Fitur baru |
| `fix:` | Perbaikan bug |
| `docs:` | Perubahan dokumentasi |
| `style:` | Perubahan tampilan/CSS |
| `refactor:` | Refaktorisasi kode |
| `chore:` | Maintenance |

---

## 📄 Lisensi

Didistribusikan di bawah **MIT License**. Lihat [`LICENSE`](LICENSE) untuk informasi lebih lanjut.

---

<!-- FOOTER BANNER -->
<img src="https://capsule-render.vercel.app/api?type=waving&color=0:2563eb,50:7c3aed,100:4f46e5&height=120&section=footer" width="100%"/>

<div align="center">

**Dibangun dengan ❤️ menggunakan [Laravel](https://laravel.com) 12**

*Neighbourhood Services — Menghubungkan warga dengan penyedia jasa terpercaya di sekitarnya*

<br/>

⭐ Jika proyek ini membantu, pertimbangkan untuk memberi **Star** di GitHub!

</div>
