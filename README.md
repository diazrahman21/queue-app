# Sistem Antrian Stand Pameran

Aplikasi Laravel untuk manajemen antrian dan pemesanan stand di pameran seni dengan 2 stand berbeda (Foto dan Lukis) yang memiliki kuota harian maksimal.

## 📋 Fitur Utama

- ✅ **Pemesanan Online**: Formulir pemesanan stand dengan validasi lengkap
- ✅ **Manajemen Kuota**: Pembatasan kuota harian per stand (Foto: 50/hari, Lukis: 30/hari)
- ✅ **Pengecekan Duplikat**: Mencegah satu email memesan stand yang sama di tanggal yang sama
- ✅ **Nomor Antri Otomatis**: Generate nomor antri unik dengan format `{KODE_STAND}{TANGGAL}{COUNTER}`
- ✅ **AJAX Form Submission**: Pemesanan tanpa reload halaman
- ✅ **Tiket Digital**: Download tiket sebagai PDF atau JPG
- ✅ **Admin DataTable**: Daftar lengkap semua pesanan dengan fitur hapus
- ✅ **REST API**: API lengkap untuk integrasi dengan aplikasi lain
- ✅ **Responsive Design**: Bootstrap 3.3.7 untuk tampilan responsif

## 📦 Requirements

- PHP 8.1+
- Composer
- MySQL/SQLite
- Node.js & npm (optional, untuk Vite)

## 🚀 Instalasi

### 1. Clone dan Setup
```bash
git clone <repository>
cd queue-app
composer install
```

### 2. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database
```bash
php artisan migrate
php artisan db:seed --class=QuotaStandSeeder
```

### 4. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000/antrian`

## 📱 Penggunaan

### Membuat Pesanan
1. Pilih Stand (Foto/Lukis)
2. Masukkan Nama dan Email
3. Pilih Tanggal
4. Klik "Buat Pesanan"
5. Download tiket (PDF/JPG)

### Melihat Daftar Pesanan
- Tab "Daftar Pesanan" menampilkan semua booking
- Fitur delete untuk menghapus pesanan

## 🔌 API Documentation

### Base URL
```
http://127.0.0.1:8000/api
```

### Endpoints

#### 1. Buat Pesanan
```http
POST /antrian
Content-Type: application/json
X-CSRF-TOKEN: {token}

{
  "nama": "Budi Santoso",
  "email": "budi@example.com",
  "tanggal_pesan": "2026-04-18",
  "kd_stand": "FT"
}
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nama": "Budi Santoso",
    "email": "budi@example.com",
    "tanggal_pesan": "2026-04-18",
    "kd_stand": "FT",
    "nomor_antri": "FT20260418001",
    "created_at": "2026-04-18T10:30:00Z"
  }
}
```

#### 2. Daftar Pesanan
```http
GET /antrian
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nama": "Budi Santoso",
      "email": "budi@example.com",
      "tanggal_pesan": "2026-04-18",
      "kd_stand": "FT",
      "nomor_antri": "FT20260418001"
    }
  ]
}
```

#### 3. Detail Pesanan
```http
GET /antrian/{id}
```

#### 4. Hapus Pesanan
```http
DELETE /antrian/{id}
X-CSRF-TOKEN: {token}
```

#### 5. Cek Kuota
```http
GET /quota?kd_stand=FT&tanggal_pesan=2026-04-18
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "kd_stand": "FT",
    "tanggal_pesan": "2026-04-18",
    "kuota_total": 50,
    "kuota_terpakai": 12,
    "kuota_tersisa": 38
  }
}
```

## 📊 Database Schema

### Tabel: `tbl_quota_stand`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary Key |
| kd_stand | VARCHAR(10) | Kode Stand (FT/LK) - Unique |
| nama_stand | VARCHAR(255) | Nama Stand |
| kuota | INT | Kuota Harian |
| created_at | TIMESTAMP | Waktu Buat |
| updated_at | TIMESTAMP | Waktu Update |

### Tabel: `tbl_antri_stand`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary Key |
| nama | VARCHAR(255) | Nama Pemesan |
| email | VARCHAR(255) | Email Pemesan |
| tanggal_pesan | DATE | Tanggal Pesanan |
| kd_stand | VARCHAR(10) | Kode Stand (FK) |
| nomor_antri | VARCHAR(50) | Nomor Antri - Unique |
| created_at | TIMESTAMP | Waktu Buat |
| updated_at | TIMESTAMP | Waktu Update |

**Indexes:**
- `(kd_stand, tanggal_pesan)` - Untuk pengecekan kuota
- `(email, kd_stand, tanggal_pesan)` - Untuk pengecekan duplikat

## 🏗️ Project Structure

```
queue-app/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── AntrianController.php
│   └── Models/
│       ├── QuotaStand.php
│       └── AntriStand.php
├── database/
│   ├── migrations/
│   │   ├── 2026_04_18_000001_create_tbl_quota_stand_table.php
│   │   └── 2026_04_18_000002_create_tbl_antri_stand_table.php
│   └── seeders/
│       └── QuotaStandSeeder.php
├── resources/
│   └── views/
│       └── antrian/
│           └── index.blade.php
├── routes/
│   ├── api.php
│   └── web.php
└── public/
    └── index.php
```

## 🛠️ Validasi Form

| Field | Rules | Pesan |
|-------|-------|-------|
| nama | required, string, max:255 | Nama harus diisi, maksimal 255 karakter |
| email | required, email, max:255 | Email harus valid |
| tanggal_pesan | required, date_format:Y-m-d | Format tanggal harus YYYY-MM-DD |
| kd_stand | required, in:FT,LK | Hanya FT atau LK |

## 🔒 Validasi Bisnis

1. **Kuota Harian**: Sistem otomatis menolak pesanan jika kuota sudah penuh
2. **Duplikat Email**: Satu email hanya bisa memesan 1x per stand per tanggal
3. **Tanggal**: Tidak bisa memesan tanggal lampau
4. **Format Nomor Antri**: `{KODE_STAND}{YYYY}{MM}{DD}{COUNTER:3digit}`
   - Contoh: `FT20260418001`, `LK20260418023`

## 📝 Teknologi

- **Backend**: Laravel 11, PHP 8.1+
- **Frontend**: HTML5, CSS3, Bootstrap 3.3.7
- **JavaScript**: jQuery 1.12.4, jQuery UI 1.13.2, DataTables 1.11.5
- **PDF/Image**: html2canvas 1.4.1, jsPDF 2.5.1
- **Database**: MySQL/SQLite
- **API**: RESTful JSON

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
