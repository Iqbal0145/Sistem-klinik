# Sistem Klinik Perusahaan

Aplikasi web untuk mencatat pemeriksaan kesehatan dan kecelakaan kerja karyawan. Aplikasi memiliki dashboard operasional, riwayat klinik, direktori karyawan, serta pengelolaan perusahaan.

## Fitur

- Dashboard menghitung kunjungan harian, pengunjung dalam pemeriksaan, dan total akun karyawan.
- Input pemeriksaan dengan pencarian nama karyawan, nama perusahaan, dan penanda karyawan tanpa riwayat.
- Riwayat pemeriksaan kesehatan beserta diagnosis, tindakan, dan catatan.
- Input laporan kecelakaan kerja yang terkait dengan perusahaan.
- Direktori dan detail karyawan hanya menampilkan akun dengan role `employee`.
- Pengelolaan perusahaan hanya untuk role `super_admin`.
- Autentikasi Laravel dan akses berbasis role.

## Teknologi dan Persyaratan

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- MySQL/MariaDB melalui Laragon/phpMyAdmin, atau SQLite untuk pengembangan
- Laravel 13, Blade, Vite, Tailwind CSS, Alpine.js, dan PHPUnit

Periksa executable PHP yang dipakai terminal dengan `php --version`. Composer, Artisan, dan PHPUnit harus dijalankan dengan PHP CLI 8.3+.

## Instalasi Lokal dengan Laragon dan MySQL

1. Jalankan Apache dan MySQL melalui Laragon. Buat database kosong, misalnya `sistem_klinik_perusahaan`, di phpMyAdmin.
2. Install dependency dan buat konfigurasi lokal:

```powershell
composer install
Copy-Item .env.example .env
```

3. Edit `.env` agar menunjuk ke database yang dibuat. Contoh konfigurasi lokal:

```dotenv
APP_NAME="Sistem Klinik Perusahaan"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_klinik_perusahaan
DB_USERNAME=root
DB_PASSWORD=
```

Isi username/password sesuai konfigurasi MySQL lokal Anda.

4. Buat application key, migrasikan tabel, dan build asset:

```powershell
php artisan key:generate
php artisan migrate
npm install
npm run build
```

5. Untuk membuat akun awal pengembangan, jalankan seeder:

```powershell
php artisan db:seed
```

Seeder saat ini membuat `admin` (`admin@example.com`) dan `superadmin` (`superadmin@example.com`) dengan password `password`. Akun dan password tersebut hanya untuk pengembangan lokal; ganti atau hapus sebelum dipakai bersama atau di produksi.

## Instalasi dengan SQLite

`.env.example` menggunakan SQLite secara default. Jika memilih SQLite, pastikan `.env` memiliki `DB_CONNECTION=sqlite` dan buat file database bila belum ada:

```powershell
if (-not (Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite }
php artisan key:generate
php artisan migrate
```

SQLite tidak muncul sebagai database MySQL di phpMyAdmin. Untuk melihat tabel melalui phpMyAdmin, gunakan konfigurasi MySQL di atas.

## Menjalankan Aplikasi

Jalankan server Laravel:

```powershell
php artisan serve
```

Untuk hot reload frontend, jalankan di terminal kedua:

```powershell
npm run dev
```

Buka URL yang dicetak oleh Artisan, biasanya `http://127.0.0.1:8000`.

## Role dan Hak Akses

| Role | Akses |
| --- | --- |
| `admin` | Dashboard, input kecelakaan/pemeriksaan, riwayat klinik, dan direktori karyawan |
| `super_admin` | Hak admin serta tambah dan lihat daftar perusahaan |
| `employee` | Akun karyawan yang dapat muncul dalam direktori dan dipilih untuk rekam klinik |

Role diperiksa oleh middleware `role`. Route aplikasi utama:

| Route | Fungsi | Role |
| --- | --- | --- |
| `/dashboard` | Ringkasan klinik | `admin`, `super_admin` |
| `/input/pemeriksaan-kesehatan` | Input pemeriksaan | `admin`, `super_admin` |
| `/riwayat-pemeriksaan` | Daftar pemeriksaan | `admin`, `super_admin` |
| `/input/kecelakaan-kerja` | Input kecelakaan kerja | `admin`, `super_admin` |
| `/karyawan` | Direktori akun karyawan | `admin`, `super_admin` |
| `/perusahaan` | Daftar dan form perusahaan | `super_admin` |
| `/login` | Login | Tamu |

Route detail pemeriksaan menggunakan pola `/riwayat-pemeriksaan/{id}`. Profil karyawan memakai `/karyawan/{id}` dan hanya menerima akun role `employee`.

## Database

- `users`: identitas login, role, dan data karyawan seperti umur, bagian, `company_name`, serta nomor karyawan.
- `companies`: nama perusahaan.
- `health_checkups`: rekam pemeriksaan yang terhubung ke `users` melalui `employee_id`.
- `work_accidents`: laporan kecelakaan; `employee_id` dan `company_id` bersifat nullable agar data lama tetap kompatibel.
- `sessions`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`: tabel pendukung Laravel.

`users.company_name` masih merupakan teks, bukan foreign key ke `companies`. Pemilih pemeriksaan menampilkan teks tersebut di samping nama akun karyawan. Lihat [arsitektur dan skema rinci](docs/ARSITEKTUR.md).

Perintah migrasi yang berguna:

```powershell
php artisan migrate:status
php artisan migrate
```

Jangan jalankan `php artisan migrate:fresh` pada database berisi data; perintah tersebut menghapus semua tabel dan isinya.

## Pengujian dan Format Kode

```powershell
php artisan test --compact
vendor\bin\pint --dirty --format agent
npm run build
```

Test suite menggunakan SQLite in-memory melalui `phpunit.xml`, sehingga tidak mengubah database MySQL lokal. Rincian alur kerja tersedia di [Panduan Pengguna](docs/PANDUAN-PENGGUNA.md).

## Struktur Direktori

- `app/Http/Controllers`: alur request untuk klinik dan perusahaan.
- `app/Http/Middleware`: pemeriksaan role.
- `app/Models`: model Eloquent dan relasi data.
- `database/migrations`: pembuatan serta perubahan skema.
- `database/seeders`: akun awal pengembangan.
- `resources/views`: halaman Blade.
- `routes`: route web dan autentikasi.
- `tests/Feature`: tes request, role, validasi, dan penyimpanan.
- `docs`: arsitektur dan panduan penggunaan.

## Keamanan dan Batasan Saat Ini

- Jangan commit `.env`, database lokal, file privat klinik, log, cache, atau kredensial. Tinjau daftar file yang akan di-commit sebelum push.
- Registrasi mandiri dinonaktifkan. Akun harus dibuat melalui proses administrasi yang disetujui; jangan membuka route registrasi kembali sebelum penetapan role dibuat aman.
- Seeder memakai password bawaan yang diketahui. Jangan gunakan akun seeder di produksi.
- Data pemeriksaan kesehatan merupakan data sensitif. Untuk produksi, wajib menyiapkan HTTPS, kontrol akses, backup terlindungi, kebijakan retensi, dan pengelolaan akun yang aman.
- Belum ada halaman untuk mendaftarkan akun karyawan; pemilih pemeriksaan hanya menampilkan akun `employee` yang sudah ada.
- Metadata Composer saat ini menyebut MIT, tetapi file `LICENSE` belum tersedia. Konfirmasi lisensi yang dimaksud dan tambahkan file lisensi sebelum repository dibuat publik.
