# SETUP Guide - TOP KEMA Telkom API

Panduan lengkap untuk setup dan menjalankan REST API TOP KEMA Telkom.

---

## 1. Persyaratan Sistem

- **PHP:** 8.2 atau lebih tinggi
- **Composer:** Versi terbaru
- **MySQL:** 8.0 atau lebih tinggi
- **Node.js:** 16 atau lebih tinggi (opsional, untuk frontend)
- **Git:** Untuk clone repository

### Verifikasi Instalasi

```bash
php --version
composer --version
mysql --version
node --version
```

---

## 2. Clone dan Install Dependencies

### Step 1: Clone Repository

```bash
git clone <repository-url> TOP
cd TOP
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Node Dependencies (Opsional)

```bash
npm install
npm run build
```

---

## 3. Konfigurasi Environment

### Step 1: Copy File .env

```bash
cp .env.example .env
```

atau di Windows:
```bash
copy .env.example .env
```

### Step 2: Generate Application Key

```bash
php artisan key:generate
```

---

## 4. Konfigurasi Database

### Step 1: Setup MySQL Database

**Menggunakan MySQL CLI:**

```sql
-- Login ke MySQL
mysql -u root -p

-- Buat database
CREATE DATABASE top_kema CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Lihat database
SHOW DATABASES;

-- Exit
EXIT;
```

**atau menggunakan MySQL Workbench/phpMyAdmin:**
- Buat database baru bernama `top_kema`
- Set character set ke `utf8mb4`
- Set collation ke `utf8mb4_unicode_ci`

### Step 2: Update File .env

Edit file `.env` dan sesuaikan database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=top_kema
DB_USERNAME=root
DB_PASSWORD=

# Jika menggunakan password MySQL
# DB_PASSWORD=your_password
```

### Step 3: Test Database Connection

```bash
php artisan db:show
```

Jika berhasil, akan menampilkan informasi database.

---

## 5. Jalankan Migration dan Seeder

### Step 1: Run Migrations

Membuat semua tabel di database:

```bash
php artisan migrate
```

### Step 2: Run Seeder (Populate Data Dummy)

```bash
php artisan db:seed
```

Atau refresh (reset database + migrate + seed):

```bash
php artisan migrate:refresh --seed
```

---

## 6. Setup File Storage

### Step 1: Create Storage Link

Membuat symbolic link storage public:

```bash
php artisan storage:link
```

### Step 2: Set Permission (Linux/Mac)

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

## 7. Jalankan Development Server

### Option A: Gunakan Artisan Serve (Recommended untuk Development)

**Terminal 1: Jalankan Web Server**
```bash
php artisan serve
```

Output:
```
Laravel development server started on http://127.0.0.1:8000
```

**Terminal 2: Jalankan Queue Listener (jika menggunakan queue)**
```bash
php artisan queue:listen
```

**Terminal 3: Jalankan Logs (opsional, untuk debugging)**
```bash
php artisan pail
```

### Option B: Gunakan Docker (Jika installed)

```bash
# Jalankan containers
docker-compose up -d

# Run migrations di container
docker-compose exec app php artisan migrate --seed
```

---

## 8. Test API Connection

### Health Check Endpoint

```bash
curl http://localhost:8000/api/health
```

Expected Response:
```json
{
  "status": "success",
  "message": "API is running",
  "timestamp": "2024-01-15T10:30:45.000000Z"
}
```

### Test Login Endpoint

**Menggunakan cURL:**

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin",
    "password": "admin123"
  }'
```

**Menggunakan Postman:**

1. Import collection (ada di folder `postman/` jika tersedia)
2. Set Base URL: `http://localhost:8000`
3. Jalankan request "Login"

---

## 9. Default Users untuk Testing

Setelah menjalankan seeder, berikut adalah user yang tersedia:

| Username | Password | Role | Fungsi |
|----------|----------|------|---------|
| admin | admin123 | Kemahasiswaan | Admin/Super User |
| dpmbem | dpmbem123 | DPMBEM | Monitoring Anggaran |

Plus:
- 5 Users dengan role **Ormawa** (untuk pengajuan proposal)
- 10 Users dengan role **Mahasiswa** (untuk pelaporan prestasi)

---

## 10. Struktur Folder Project

```
TOP/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/      # API Controllers
│   │   ├── Middleware/           # Custom Middleware (CheckRole)
│   │   ├── Requests/             # Form Validation Requests
│   │   └── Resources/            # API Response Resources
│   ├── Models/                   # Eloquent Models
│   └── Helpers/                  # Helper Functions
├── database/
│   ├── migrations/               # Database Migrations
│   ├── factories/                # Factory untuk dummy data
│   └── seeders/                  # Database Seeders
├── routes/
│   ├── api.php                   # API Routes
│   └── web.php                   # Web Routes
├── storage/
│   └── app/public/               # File uploads
├── .env                          # Environment Configuration
├── API_DOCUMENTATION.md          # API Documentation
├── SETUP_MYSQL.md               # MySQL Setup (alternative)
└── README.md                     # Project README
```

---

## 11. Development Workflow

### Update Model dan Schema

Jika menambahkan field baru:

1. **Edit Migration File** di `database/migrations/`
   ```bash
   php artisan make:migration add_new_field_to_users_table
   ```

2. **Edit Model** di `app/Models/`
   ```php
   protected $fillable = [..., 'new_field'];
   ```

3. **Edit Factory** di `database/factories/`
   ```php
   'new_field' => fake()->word(),
   ```

4. **Run Migration:**
   ```bash
   php artisan migrate
   ```

### Membuat Endpoint Baru

1. **Create Controller:**
   ```bash
   php artisan make:controller Api/NewController -m Model
   ```

2. **Create FormRequest:**
   ```bash
   php artisan make:request StoreNewRequest
   ```

3. **Create Resource:**
   ```bash
   php artisan make:resource NewResource
   ```

4. **Add Routes** di `routes/api.php`

5. **Test dengan Postman/cURL**

### Debugging

```bash
# Clear all caches
php artisan cache:clear

# Reset database
php artisan migrate:reset

# Refresh & seed
php artisan migrate:refresh --seed

# View routes
php artisan route:list --path=api

# Tinker (Interactive Shell)
php artisan tinker
```

---

## 12. Production Deployment

### Prerequisites untuk Production

1. **Update .env:**
   ```env
   APP_DEBUG=false
   APP_ENV=production
   ```

2. **Install Dependencies (Production):**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install --production
   npm run build
   ```

3. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```

4. **Optimize Caches:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Run Migrations:**
   ```bash
   php artisan migrate --env=production
   php artisan db:seed --class=DatabaseSeeder
   ```

6. **Set Permissions:**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

### Deploy menggunakan Docker

Lihat `docker-compose.yml` dan `Dockerfile` (jika tersedia).

---

## 13. Troubleshooting

### Error: "Could not find driver"

Solusi:
```bash
# Cek PHP extensions
php -m | grep -i pdo

# Jika tidak ada, install di php.ini
# Uncomment: extension=pdo_mysql
# Restart PHP
```

### Error: "SQLSTATE[HY000]"

Solusi:
1. Pastikan MySQL running
2. Cek database credentials di .env
3. Pastikan database sudah dibuat

### Error: "Class not found"

Solusi:
```bash
composer dump-autoload
php artisan cache:clear
```

### File Upload Error

Solusi:
```bash
# Ensure storage/app/public is writable
chmod -R 755 storage
php artisan storage:link
```

### Token Issues

Solusi:
```bash
php artisan tinker
# Dalam tinker:
>>> User::find(1)->tokens()->delete()
>>> exit()
```

---

## 14. API Testing dengan Postman

### Import Collection

1. Download Postman dari https://www.postman.com/
2. Click **Import** → **Upload Files**
3. Select file `postman/TOP-KEMA-Collection.json` (jika ada)
4. Atau setup manual sesuai API_DOCUMENTATION.md

### Setup Environment Variables

1. Click **New Environment**
2. Set variables:
   - `base_url`: `http://localhost:8000/api/v1`
   - `token`: (akan diisi setelah login)
   - `admin_token`: (token admin untuk testing)

### Pre-request Script untuk Auto Token

```javascript
// Jika token sudah ada di environment
if (!pm.environment.get('token')) {
  // Login untuk mendapat token
  pm.sendRequest({
    url: pm.environment.get('base_url') + '/auth/login',
    method: 'POST',
    header: {
      'Content-Type': 'application/json'
    },
    body: {
      mode: 'raw',
      raw: JSON.stringify({
        username: 'admin',
        password: 'admin123'
      })
    }
  }, (err, response) => {
    if (!err) {
      var token = response.json().data.token;
      pm.environment.set('token', token);
    }
  });
}
```

---

## 15. Performance Optimization

### Database Indexing

Indexes sudah dibuat di migrations untuk:
- Foreign Keys
- Status fields
- User IDs

### Query Optimization

Gunakan eager loading untuk menghindari N+1 queries:
```php
ProposalKegiatan::with('user', 'lpj', 'revisions')->get();
```

### Caching

```php
// Cache daftar template (1 jam)
Cache::remember('templates', 3600, function () {
    return TemplateDokumen::all();
});
```

---

## 16. Security Checklist

- [x] Gunakan Laravel Sanctum untuk API authentication
- [x] HTTPS wajib di production
- [x] Set CORS properly
- [x] Hash password dengan bcrypt
- [x] Validate semua input dengan FormRequest
- [x] Implement rate limiting
- [x] Remove debug info dari production
- [x] Use environment variables untuk sensitive data

---

## 17. Backup Database

```bash
# Backup
mysqldump -u root -p top_kema > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore
mysql -u root -p top_kema < backup.sql
```

---

## Support & Help

- **API Documentation:** Lihat `API_DOCUMENTATION.md`
- **Issues:** Hubungi tim development
- **Database Issues:** Lihat `SETUP_MYSQL.md`

---

Last Updated: 2024
