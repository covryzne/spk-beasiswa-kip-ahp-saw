# 🎓 SPK Beasiswa KIP - Sistem Pendukung Keputusan

Sistem Pendukung Keputusan untuk Seleksi Penerima Beasiswa KIP (Kartu Indonesia Pintar) menggunakan metode **AHP (Analytical Hierarchy Process)** dan **SAW (Simple Additive Weighting)** yang dibangun dengan **Laravel 11** dan **Filament 3**.

---

## 📋 **Daftar Isi**

-   [Fitur Utama](#-fitur-utama)
-   [Role & Permissions](#-role--permissions)
-   [Persyaratan Sistem](#-persyaratan-sistem)
-   [Instalasi & Setup](#-instalasi--setup)
-   [Konfigurasi Database](#-konfigurasi-database)
-   [Menjalankan Aplikasi](#-menjalankan-aplikasi)
-   [Panduan Penggunaan](#-panduan-penggunaan)
-   [Kredensial Default](#-kredensial-default)
-   [Teknologi](#-teknologi)
-   [Kontribusi](#-kontribusi)

---

## 🚀 **Fitur Utama**

### **Metode Perhitungan:**

-   **AHP (Analytical Hierarchy Process)** - Untuk menghitung bobot kriteria
-   **SAW (Simple Additive Weighting)** - Untuk ranking final calon mahasiswa
-   **Consistency Ratio Check** - Validasi konsistensi matriks perbandingan
-   **Real-time Calculation** - Perhitungan otomatis saat data berubah

### **Management System:**

-   **Multi-Role Access** (Admin & User)
-   **CRUD Management** untuk semua data
-   **Dynamic Quota Setting** - Pengaturan kuota beasiswa fleksibel
-   **Advanced Filtering & Search**
-   **Export/Import Capabilities**
-   **Responsive Design** - Mobile friendly

---

## 👥 **Role & Permissions**

### **🔐 Admin Panel** (`/admin`)

**Role:** Administrator SPK

**Menu & Fitur:**

-   **Dashboard**

    -   Overview statistik sistem
    -   Chart distribusi data
    -   Widget monitoring real-time

-   **Master Data**

    -   **Kriteria** - Management kriteria penilaian (C1-C5)
    -   **Calon Mahasiswa** - Data kandidat beasiswa
    -   **Matriks AHP** - Perbandingan berpasangan kriteria

-   **Perhitungan**

    -   **Perhitungan AHP** - Hitung bobot kriteria + consistency check
    -   **Perhitungan SAW** - Proses ranking calon mahasiswa

-   **Perankingan**

    -   **Hasil Seleksi** - View ranking final
    -   **Update Kuota** - Pengaturan kuota beasiswa via modal
    -   **Status Management** - Ubah status diterima/ditolak

-   **User Management**
    -   CRUD user accounts
    -   Role assignment

### **👤 User Panel** (`/user`)

**Role:** Calon Mahasiswa / Viewer

**Menu & Fitur:**

-   **Dashboard**

    -   Statistik personal
    -   Status seleksi

-   **Data Kandidat**

    -   **Calon Mahasiswa** - View data kandidat (read-only)

-   **Perankingan**
    -   **Hasil Ranking** - View hasil ranking & status (read-only)

---

## 💻 **Persyaratan Sistem**

-   **PHP** >= 8.2
-   **Composer** >= 2.0
-   **Node.js** >= 18.0
-   **NPM/Yarn**
-   **Database:** MySQL/MariaDB/PostgreSQL/SQLite
-   **Web Server:** Apache/Nginx/Laravel Valet

---

## 🛠 **Instalasi & Setup**

## ⚡ **Quick Setup (Recommended)**

**Setup dalam 5 langkah sederhana menggunakan MySQL (Laragon/XAMPP):**

```bash
# 1. Clone & navigate
git clone https://github.com/your-username/spk-beasiswa-kip.git
cd spk-beasiswa-kip

# 2. Install dependencies
composer install && npm install

# 3. Setup environment (MySQL untuk Laragon/XAMPP)
cp .env.example .env
php artisan key:generate

# 4. Buat database MySQL
# Di phpMyAdmin atau MySQL command line:
# CREATE DATABASE spk_beasiswa_kip;

# 5. Setup database & sample data
php artisan migrate:fresh --seed

# 6. Run application
php artisan serve
```

🎉 **Selesai!** Buka `http://localhost:8000/admin` dan login dengan `adminspk@gmail.com` / `password`

> **💡 Alternative:** Untuk SQLite (tanpa MySQL), lihat [Setup Database Detail](#🗄-setup-database-detail)

---

## 🔧 **Manual Setup (Advanced)**

### **1. Clone Repository**

```bash
git clone https://github.com/your-username/spk-beasiswa-kip.git
cd spk-beasiswa-kip
```

### **2. Install Dependencies**

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### **3. Environment Configuration**

```bash
# Copy environment file (sudah dikonfigurasi dengan MySQL untuk Laragon/XAMPP)
cp .env.example .env

# Generate application key
php artisan key:generate
```

💡 **Good News:** File `.env.example` sudah dikonfigurasi dengan **MySQL** untuk Laragon/XAMPP, plus opsi **SQLite** untuk development cepat!

### **4. Pilih Database (Edit .env jika perlu)**

#### **Opsi A: MySQL dengan Laragon/XAMPP (Default)**

File `.env.example` sudah dikonfigurasi untuk MySQL. Hanya perlu buat database:

```bash
# Di MySQL/phpMyAdmin, buat database baru:
CREATE DATABASE spk_beasiswa_kip;

# .env sudah siap pakai untuk Laragon/XAMPP:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spk_beasiswa_kip
DB_USERNAME=root
DB_PASSWORD=                # kosong untuk Laragon/XAMPP default
```

#### **Opsi B: SQLite (Quick Setup - Zero Config)**

Jika ingin pakai SQLite (tanpa install MySQL), edit `.env`:

```env
# Comment MySQL config dan uncomment SQLite:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=spk_beasiswa_kip
# DB_USERNAME=root
# DB_PASSWORD=

# Gunakan SQLite
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
```

---

## 🗄 **Setup Database Detail**

### **Opsi 1: MySQL/MariaDB (Laragon/XAMPP)**

```bash
# 1. Start Laragon/XAMPP
# 2. Buka phpMyAdmin atau MySQL command line
mysql -u root -p
CREATE DATABASE spk_beasiswa_kip;
exit

# 3. File .env sudah dikonfigurasi untuk MySQL (default)
```

### **Opsi 2: SQLite (Development/Testing)**

```bash
# 1. Buat file database SQLite
touch database/database.sqlite

# 2. Edit .env - ganti dari MySQL ke SQLite
# (Uncomment bagian SQLite, comment bagian MySQL)
```

### **Run Migration & Seeder**

```bash
# Jalankan migration (buat tabel)
php artisan migrate

# Jalankan seeder (isi data awal)
php artisan db:seed

# Atau reset database + seeder sekaligus
php artisan migrate:fresh --seed
```

---

## 🎯 **Menjalankan Aplikasi**

### **1. Start Development Server**

```bash
# Laravel Development Server
php artisan serve
# Akses: http://localhost:8000

# Atau dengan custom host/port
php artisan serve --host=0.0.0.0 --port=8080
```

### **2. Compile Assets (Optional)**

```bash
# Development mode
npm run dev

# Production build
npm run build

# Watch mode (auto-compile saat file berubah)
npm run dev -- --watch
```

### **3. Background Jobs (Optional)**

```bash
# Jika menggunakan queue
php artisan queue:work
```

---

## 📖 **Panduan Penggunaan**

### **Setup Awal Sistem:**

1. **Login sebagai Admin**

    - Buka `/admin`
    - Login: `adminspk@gmail.com` / `password`

2. **Verifikasi Data Master**

    - Cek **Master Data > Kriteria** (5 kriteria: C1-C5)
    - Cek **Master Data > Calon Mahasiswa** (10 kandidat: A01-A10)
    - Cek **Master Data > Matriks AHP** (25 perbandingan)

3. **Hitung Bobot Kriteria**

    - Masuk **Perhitungan > Perhitungan AHP**
    - Klik **"Hitung AHP"**
    - Pastikan **Consistency Ratio ≤ 0.1**

4. **Proses Ranking SAW**

    - Masuk **Perhitungan > Perhitungan SAW**
    - Klik **"Hitung SAW"**
    - Review hasil ranking

5. **Set Kuota Beasiswa**
    - Masuk **Perankingan > Hasil Seleksi**
    - Klik **"Ubah Kuota Beasiswa"**
    - Tentukan jumlah mahasiswa yang diterima

### **Workflow Normal:**

1. **Input/Update Data** → 2. **Hitung AHP** → 3. **Hitung SAW** → 4. **Set Kuota** → 5. **Review Hasil**

---

## 🔑 **Kredensial Default**

| Role      | Email              | Password | Panel    |
| --------- | ------------------ | -------- | -------- |
| **Admin** | adminspk@gmail.com | password | `/admin` |
| **User**  | user@gmail.com     | password | `/user`  |

⚠️ **PENTING:** Ubah password default setelah login pertama!

---

## 🔄 **Reset Database Commands**

```bash
# Reset semua (fresh start)
php artisan migrate:fresh --seed

# Reset hanya users
php artisan db:seed --class=UserSeeder

# Reset hanya data SPK
php artisan db:seed --class=SPKDataSeeder

# Reset database tanpa seeder
php artisan migrate:fresh
```

---

## 🛠 **Teknologi**

### **Backend:**

-   **Laravel 11** - PHP Framework
-   **Filament 3** - Admin Panel Framework
-   **MySQL/SQLite** - Database
-   **PHP 8.2+** - Programming Language

### **Frontend:**

-   **Livewire 3** - Full-stack Framework
-   **Alpine.js** - JavaScript Framework
-   **Tailwind CSS** - CSS Framework
-   **Filament UI Components** - Pre-built Components

### **Tools & Packages:**

-   **Composer** - PHP Dependency Manager
-   **NPM/Vite** - Asset Bundling
-   **Artisan** - Laravel CLI

---

## 📊 **Data Sample**

### **Kriteria Penilaian:**

| Kode   | Nama                   | Jenis   | Deskripsi                           |
| ------ | ---------------------- | ------- | ----------------------------------- |
| **C1** | Penghasilan Orang Tua  | Cost    | Semakin rendah semakin baik         |
| **C2** | Kondisi Tempat Tinggal | Cost    | Jarak dari kampus (1=jauh, 5=dekat) |
| **C3** | Hasil Tes Prestasi     | Benefit | Skor tes (0-100)                    |
| **C4** | Hasil Tes Wawancara    | Benefit | Skor wawancara (0-100)              |
| **C5** | Rata-Rata Nilai        | Benefit | Nilai akademik (0-100)              |

### **Sample Kandidat:**

-   **A01-A10:** Ahmad Fauzi, Siti Nurhaliza, Budi Santoso, dll.
-   **Data Realistis:** Variasi penghasilan, lokasi, dan prestasi akademik

---

## 🚀 **Development**

### **Struktur Project:**

```
spk-beasiswa-kip/
├── app/
│   ├── Filament/
│   │   ├── Admin/          # Admin panel resources
│   │   └── User/           # User panel resources
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic (AHP, SAW)
│   └── Http/
├── database/
│   ├── migrations/         # Database schema
│   └── seeders/           # Sample data
├── resources/
│   ├── views/             # Blade templates
│   └── css/js/            # Frontend assets
└── routes/                # Application routes
```

### **Custom Commands:**

```bash
# Debug hasil seleksi
php artisan debug:hasil-seleksi

# Generate sample data
php artisan db:seed --class=SPKDataSeeder
```

---

## 🤝 **Kontribusi**

1. Fork repository
2. Buat feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

---

## 📝 **License**

Distributed under the MIT License. See `LICENSE` for more information.

---

## � **Troubleshooting**

### **Common Issues & Solutions:**

#### **1. Database Connection Error**

```bash
# Untuk SQLite
touch database/database.sqlite
php artisan migrate:fresh --seed

# Untuk MySQL
# Pastikan database sudah dibuat dan kredensial di .env benar
```

#### **2. Permission Denied (Storage/Cache)**

```bash
# Windows
chmod -R 775 storage bootstrap/cache

# Linux/Mac
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### **3. Composer Dependencies Error**

```bash
# Clear cache dan reinstall
composer clear-cache
rm -rf vendor
rm composer.lock
composer install
```

#### **4. NPM/Asset Compilation Error**

```bash
# Clear cache dan reinstall
npm cache clean --force
rm -rf node_modules
rm package-lock.json
npm install
npm run build
```

#### **5. AHP Calculation Issues**

```bash
# Debug dan reset perhitungan
php artisan debug:hasil-seleksi
php artisan migrate:fresh --seed
```

#### **6. Filament Panel Not Loading**

```bash
# Clear all caches
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

### **Performance Tips:**

```bash
# Production optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

---

## �📞 **Support**

Jika mengalami masalah:

1. **Check Documentation** - Baca README ini
2. **Check Laravel Logs** - `storage/logs/laravel.log`
3. **Run Debug Commands** - `php artisan debug:hasil-seleksi`
4. **Reset Database** - `php artisan migrate:fresh --seed`

---

## 🎉 **Selamat Menggunakan!**

Sistem SPK Beasiswa KIP siap digunakan untuk membantu proses seleksi penerima beasiswa yang objektif dan transparan!

**Happy Coding!** 🚀
