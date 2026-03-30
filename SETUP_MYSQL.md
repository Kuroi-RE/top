# 🚀 Panduan Setup Proyek Laravel (Tim)

Dokumen ini berisi langkah-langkah untuk menyiapkan lingkungan pengembangan (development environment) agar proyek ini berjalan lancar di perangkat masing-masing.

---

## 🛠️ Prasyarat (Prerequisites)

Pastikan perangkat Anda sudah terinstall:
* **PHP >= 8.2**
* **Composer**
* **Node.js & NPM**
* **Git**
* **MySQL Server** (Atau Laragon/XAMPP untuk Windows)

---

## 📥 1. Clone & Install Dependency

Jalankan perintah berikut di terminal/command prompt:

```bash
# Clone repository (jika belum)
git clone <url-repository-anda>
cd <nama-folder-proyek>

# Install library PHP
composer install

# Install library Frontend
npm install
npm run build
```

---

## 🗄️ 2. Konfigurasi Database (Pilih Sesuai OS)

### 🐧 Untuk Pengguna Ubuntu (Linux)
Masuk ke MySQL dan siapkan database:
```bash
sudo mysql
```
Di dalam MySQL, jalankan:
```sql
CREATE DATABASE IF NOT EXISTS top_db;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'password_anda';
GRANT ALL PRIVILEGES ON top_db.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 🪟 Untuk Pengguna Windows (XAMPP/Laragon)
1.  Buka **phpMyAdmin** atau **HeidiSQL**.
2.  Buat database baru dengan nama: `top_db`.
3.  User default biasanya `root` tanpa password.

---

## ⚙️ 3. Setup Environment (.env)

Salin file contoh konfigurasi dan sesuaikan isinya:

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan sesuaikan bagian database berikut:

**Ubuntu (Linux):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=top_db
DB_USERNAME=laravel
DB_PASSWORD=password_anda
```

**Windows (XAMPP/Laragon):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=top_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🚀 4. Finalisasi

Jalankan migrasi database untuk membuat tabel:

```bash
php artisan migrate
```

Jalankan server lokal:

```bash
php artisan serve
```

Buka browser di: `http://127.0.0.1:8000`

---

## 📝 Catatan Tambahan
- Jika ada perubahan pada database (migrasi baru), jalankan `php artisan migrate`.
- Jika ada perubahan pada file CSS/JS, jalankan `npm run dev` saat pengembangan.
