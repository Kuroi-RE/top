# TOP KEMA Telkom - REST API Documentation

## Sistem Informasi Organisasi dan Prestasi Kemahasiswaan

REST API lengkap untuk Telkom University Purwokerto menggunakan Laravel 12 dengan Laravel Sanctum.

---

## Table of Contents

- [Role Hierarchy](#role-hierarchy)
- [Setup Instalasi](#setup-instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Authentication](#authentication)
- [API Endpoints](#api-endpoints)
- [Error Handling](#error-handling)
- [Testing Endpoints](#testing-endpoints)

---

## Role Hierarchy

### TOP KEMA memiliki 5 role dengan hierarchy sebagai berikut:

| No  | Role          | Deskripsi                                         | Permission                                                      |
| --- | ------------- | ------------------------------------------------- | --------------------------------------------------------------- |
| 1   | Super Admin   | Developer - Akses penuh ke seluruh sistem         | Assign semua role, nonaktifkan user                             |
| 2   | Kemahasiswaan | Dinas Kemahasiswaan - Mengelola Ormawa & Prestasi | Assign role (kecuali Super Admin), nonaktifkan user, monitoring |
| 3   | DPMBEM        | Dewan Prestasi Mahasiswa - Memonitor prestasi     | Lihat laporan prestasi dan statistik                            |
| 4   | Ormawa        | Organisasi Ormawa (UKM atau Himpunan Prodi)       | Upload proposal, LPJ, lihat informasi                           |
| 5   | Mahasiswa     | Default role saat registrasi                      | Upload prestasi, lihat informasi                                |

**Permission Rules:**

- **Super Admin**: Dapat assign semua role termasuk Super Admin
- **Kemahasiswaan**: Hanya dapat assign Ormawa, Mahasiswa, dan DPMBEM (tidak bisa assign Super Admin)
- **Kemahasiswaan & Super Admin**: Dapat menonaktifkan akun user
- **Ormawa**: Memiliki sub-tipe: `institusi` (UKM) atau `prodi` (Himpunan Mahasiswa)

---

## Setup Instalasi

### Requirements

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 16+ (untuk development)

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd TOP
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Setup Environment**

    ```bash
    copy .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi Database** (lihat bagian berikut)

5. **Run Migrations & Seeder**

    ```bash
    php artisan migrate --seed
    ```

6. **Start Development Server**
    ```bash
    php artisan serve
    ```

---

## Konfigurasi Database

### MySQL Setup

1. **Buat Database**

    ```sql
    CREATE DATABASE top_kema CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ```

2. **Update .env**

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=top_kema
    DB_USERNAME=root
    DB_PASSWORD=
    ```

3. **Jalankan Migration**
    ```bash
    php artisan migrate --seed
    ```

---