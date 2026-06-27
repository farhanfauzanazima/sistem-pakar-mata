# 👁️ Sistem Pakar Diagnosis Penyakit Mata

Aplikasi web sistem pakar untuk diagnosis awal penyakit mata menggunakan metode **Certainty Factor (CF)** dan **Forward Chaining**, dibangun dengan Laravel 12.

> **Referensi Jurnal:** Joni Warta, Hendarman Lubis — *Sistem Pakar untuk Diagnosis Penyakit Mata dengan Certainty Factor dan Forward Chaining* — JISICOM Vol.9 No.2 (2025) · DOI: 10.52362/jisicom.v9i2.2218

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Stack Teknologi](#stack-teknologi)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Akun Default](#akun-default)
- [Struktur Folder](#struktur-folder)
- [Alur Diagnosa](#alur-diagnosa)
- [Metode Certainty Factor](#metode-certainty-factor)
- [Fitur per Role](#fitur-per-role)
- [Hasil Pengujian Sistem](#hasil-pengujian-sistem)

---

## Fitur Utama

### Untuk Pasien / User
- Diagnosis penyakit mata tanpa registrasi akun
- Input gejala dengan skala frekuensi (Sangat Sering, Sering, Jarang, Tidak Pernah)
- Hasil diagnosis dengan nilai CF dan tingkat keyakinan
- Download hasil diagnosis dalam format PDF
- Riwayat diagnosis dengan proteksi PIN
- Reset PIN (dengan konsekuensi hapus riwayat)

### Untuk Admin
- Dashboard statistik dengan grafik interaktif (Chart.js)
- Manajemen data penyakit, gejala, dan aturan CF
- Kelola seluruh data diagnosis pasien
- Filter dan ekspor data ke Excel
- Manajemen akun admin (khusus Super Admin)

---

## Stack Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend Framework | Laravel 12 (PHP 8.3) |
| Database | MySQL 8.0 |
| Frontend | Bootstrap 5 + Alpine.js |
| PDF Generator | barryvdh/laravel-dompdf |
| Excel Export | maatwebsite/excel |
| Grafik | Chart.js 4 |
| Debugging | barryvdh/laravel-debugbar |

---

## Arsitektur Sistem

```
sistem-pakar-mata/
├── app/
│   ├── Exports/
│   │   └── DiagnosaExport.php          # Export Excel data diagnosa
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  # Controller panel admin
│   │   │   │   ├── AdminController.php
│   │   │   │   ├── AturanController.php
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── DiagnosaController.php
│   │   │   │   ├── GejalaController.php
│   │   │   │   └── PenyakitController.php
│   │   │   └── User/                   # Controller halaman user
│   │   │       ├── BerandaController.php
│   │   │       ├── DiagnosaController.php
│   │   │       ├── HasilController.php
│   │   │       ├── RiwayatController.php
│   │   │       └── TentangController.php
│   │   └── Middleware/
│   │       ├── AdminAuth.php           # Guard sesi admin
│   │       └── SuperAdminAuth.php      # Guard khusus super admin
│   ├── Models/
│   │   ├── Admin.php
│   │   ├── Aturan.php
│   │   ├── DetailDiagnosa.php
│   │   ├── Gejala.php
│   │   ├── HasilDiagnosa.php
│   │   ├── Pasien.php
│   │   └── Penyakit.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/
│       └── CertaintyFactorService.php  # Engine CF + Forward Chaining
├── database/
│   ├── migrations/                     # Semua migrasi tabel
│   └── seeders/                        # Data awal dari jurnal
├── resources/views/
│   ├── admin/                          # View panel admin
│   ├── layouts/
│   │   ├── admin.blade.php             # Layout sidebar admin
│   │   └── user.blade.php             # Layout navbar user
│   └── user/                          # View halaman user
├── routes/
│   ├── admin.php                       # Route panel admin
│   └── web.php                         # Route halaman user
└── storage/
    └── app/public/jurnal/             # PDF jurnal referensi
```

---

## Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.x
- MySQL >= 8.0
- Node.js (opsional, untuk asset)
- XAMPP / Laragon / Herd

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/sistem-pakar-mata.git
cd sistem-pakar-mata
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Salin File Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Buat Database

Buat database MySQL baru:

```sql
CREATE DATABASE sistem_pakar_mata CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Konfigurasi `.env`

```env
APP_NAME="Sistem Pakar Mata"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_pakar_mata
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi dan Seeder

```bash
php artisan migrate:fresh --seed
```

### 7. Buat Storage Link

```bash
php artisan storage:link
```

### 8. (Opsional) Tambahkan PDF Jurnal

Salin file PDF jurnal ke:

```
storage/app/public/jurnal/jurnal-sistem-pakar-mata.pdf
```

### 9. Jalankan Server

```bash
php artisan serve
```

Akses di `http://localhost:8000`

---

## ⚙️ Konfigurasi

### URL Website Jurnal

Tambahkan di `.env`:

```env
JURNAL_WEBSITE_URL=https://journal.stmikjayakarta.ac.id/index.php/jisicom/article/view/2218
```

### Konfigurasi Mail (opsional)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=yourpassword
```

---

### Akses Panel Admin

```
http://localhost:8000/admin/login
```

---

## Struktur Database

| Tabel | Deskripsi |
|-------|-----------|
| `admins` | Akun administrator sistem |
| `penyakit` | Data 4 penyakit mata |
| `gejala` | Data 12 gejala penyakit mata |
| `aturan` | Relasi gejala-penyakit + nilai CF Pakar |
| `pasien` | Data pasien (nama, HP, PIN) |
| `hasil_diagnosa` | Hasil diagnosis per pasien |
| `detail_diagnosa` | Detail gejala per hasil diagnosis |

### Data Awal (dari Jurnal)

**Penyakit:**
- P001 — Katarak
- P002 — Glaukoma
- P003 — Konjungtivitis
- P004 — Retinopati Diabetik

**Gejala:** G001–G012 (12 gejala)

**Aturan CF:** 14 relasi gejala-penyakit

---

## Alur Diagnosa

```
User → Isi Nama + HP + PIN
     → Pilih Gejala + Frekuensi
     → Engine CF + Forward Chaining
     → Tampilkan Hasil (Penyakit + CF%)
     → Download PDF (opsional)
     → Simpan ke Riwayat
```

---

## Metode Certainty Factor

### Nilai CF User berdasarkan Frekuensi

| Frekuensi | Nilai CF User |
|-----------|---------------|
| Sangat Sering | 1.0 |
| Sering | 0.6 |
| Jarang | 0.3 |
| Tidak Pernah | 0.0 |

### Rumus CF per Gejala

```
CF(gejala) = CF_User × CF_Pakar
```

### Rumus Kombinasi CF

```
CF_combine = CF1 + CF2 × (1 - CF1)
```

### Contoh Perhitungan (Glaukoma)

```
G004 Nyeri mata          → CF = 1.0 × 0.90 = 0.900
G005 Penglihatan terowongan → CF = 0.6 × 0.85 = 0.510
G006 Mata merah          → CF = 0.3 × 0.70 = 0.210
G012 Lingkaran cahaya    → CF = 0.6 × 0.75 = 0.450

CF(1,2) = 0.900 + 0.510 × (1 - 0.900) = 0.951
CF(1,2,3) = 0.951 + 0.210 × (1 - 0.951) = 0.961
CF(1,2,3,4) = 0.961 + 0.450 × (1 - 0.961) = 0.979

Hasil: Glaukoma → CF = 0.979 (98%)
```

### Tingkat Keyakinan

| CF (%) | Label |
|--------|-------|
| ≥ 80% | Sangat Tinggi |
| ≥ 60% | Tinggi |
| ≥ 40% | Sedang |
| ≥ 20% | Rendah |
| < 20% | Sangat Rendah |

---

## Fitur per Role

### Super Admin
- ✅ Semua fitur Admin
- ✅ Manajemen akun admin (tambah, edit, hapus)
- ✅ Tidak dapat dihapus dari sistem

### Admin
- ✅ Login ke dashboard
- ✅ CRUD penyakit, gejala, aturan CF
- ✅ Lihat & hapus data diagnosa pasien
- ✅ Filter & ekspor data ke Excel
- ✅ Dashboard statistik + grafik
- ❌ Tidak dapat mengakses Manajemen Admin

### User / Pasien
- ✅ Diagnosis penyakit mata
- ✅ Download hasil PDF
- ✅ Lihat riwayat (dengan PIN)
- ✅ Reset PIN
- ❌ Tidak perlu registrasi akun

---

## Hasil Pengujian Sistem

| Metrik | Nilai |
|--------|-------|
| Akurasi | 92% |
| Presisi | 90% |
| Recall | 88% |
| F1-Score | 89% |

> Sumber: Jurnal referensi (Joni Warta & Hendarman Lubis, 2025)

---

## Keamanan

- Password admin di-hash dengan **bcrypt**
- PIN pasien di-hash dengan **bcrypt**
- Lockout otomatis setelah **3x PIN salah** (5 menit)
- CSRF Protection pada semua form
- SQL Injection protection via Eloquent ORM
- XSS Protection via Blade escaping
- Session-based authentication untuk admin

---

## Catatan Teknis

1. **Sistem menggunakan Laravel 13** — beberapa API berbeda dari dokumentasi resmi Laravel 12.
2. **Route admin** didaftarkan via `bootstrap/app.php` dengan prefix `/admin`.
3. **Super Admin** ditandai dengan kolom `is_super_admin = true` di tabel `admins`.
4. **PDF jurnal** disimpan di `storage/app/public/jurnal/` — perlu `php artisan storage:link`.
5. **Carbon locale** di-set ke `id` (Indonesia) di `AppServiceProvider`.
6. **Pagination** menggunakan Bootstrap 5 (`Paginator::useBootstrapFive()`).

---

## Developer

**Farhan Fauzan Azima**
- GitHub: [@farhanfauzanazima](https://github.com/farhanfauzanazima)

---

## Lisensi

Proyek ini dibuat untuk keperluan pengembangan sistem pakar berbasis jurnal.

---

*Sistem Pakar Diagnosis Penyakit Mata — Laravel 12 + Certainty Factor + Forward Chaining*