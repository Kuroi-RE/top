# Agent Context - TOPKEMA Project

## Konteks Proyek
Platform manajemen kegiatan organisasi mahasiswa (Ormawa) dan prestasi mahasiswa Telkom University Purwokerto. Backend: Laravel 12 + Sanctum + Spatie Permission. Frontend: Blade (monolith), dengan rencana migrasi ke React (top_frontend).

## Arsitektur
- **Backend**: Laravel 12, Sanctum (token-based), Spatie Permission
- **Database**: MySQL (top_db di Laragon, host: 127.0.0.1:3306)
- **Frontend saat ini**: Blade templates (resources/views/)
- **Frontend baru**: React + Tailwind CSS (../top_frontend) - masih fase desain
- **Base URL Laragon**: http://top.test/
- **API Base**: http://top.test/api/v1
- **Auth API**: Bearer Token (bukan cookie SPA)

## Role Hierarchy
1. Super Admin - akses penuh
2. Kemahasiswaan (Admin) - kelola semua, verifikasi proposal/LPJ/prestasi/publikasi
3. DPMBEM - monitoring & view
4. Ormawa Institusi (UKM) - proposal, LPJ, prestasi, publikasi
5. Ormawa Prodi (Himpunan) - sama dengan Ormawa Institusi
6. Mahasiswa - prestasi dan proposal dana (terbatas)

## Rules Penting
1. **Backend-first**: Setiap perubahan yang menyentuh database dimulai dari Migration → Model → Request → Controller → API Resource → View/Frontend
2. **Permission check**: Setiap fitur harus cek permission di: (a) API route middleware, (b) Web route guard, (c) Blade view `@can()`
3. **Status enum LPJ**: `['Menunggu', 'Revisi', 'Disetujui']` - bukan 'Pending'/'Approved'/'Rejected'
4. **Approved statuses untuk anggaran**: `['Approved', 'Cek LPJ', 'Revisi LPJ', 'Selesai']` - semua status pasca-approval punya anggaran_disetujui
5. **Symlink storage**: `php artisan storage:link` diperlukan di Laragon
6. **Kuota publikasi**: 3 per minggu, exclude status Rejected dari hitungan

## Permission Map (Spatie)
| Permission | Role Default |
|---|---|
| Create Publikasi | Ormawa Prodi, Ormawa Institusi |
| View Publikasi | Ormawa, Kemahasiswaan, DPMBEM |
| Edit Publikasi | Ormawa, Kemahasiswaan |
| Delete Publikasi | Ormawa, Kemahasiswaan, Admin |
| Approve Publikasi | Kemahasiswaan, Admin, Super Admin |
| Create Proposal Kegiatan | Ormawa, Mahasiswa |
| View Proposal Kegiatan | Semua kecuali default Mahasiswa terbatas |
| Create Prestasi | Ormawa, Mahasiswa |
| ... (lihat config/permissions.php untuk lengkapnya) |

## Bug Fixes History
1. LPJ file upload hanya accept PDF → fixed: accept jpg,jpeg,png,webp juga
2. status_lpj enum mismatch 'Pending' → fixed: gunakan 'Menunggu'
3. Storage 403 → fixed: php artisan storage:link
4. Monitoring anggaran = 0 → fixed: whereIn approved statuses
5. Nama ormawa di tabel = nama orang → fixed: gunakan ormawa_name dari UserResource
6. weekCount tidak konsisten → fixed: sentralisasi di route PHP, exclude Rejected
7. HTML content publikasi tampil sebagai teks → fixed: JSON store di script tag
8. Tombol hapus publikasi 403 → fixed: tambah Delete Publikasi ke config permissions Ormawa
9. Ormawa lihat semua prestasi → fixed: PrestasiController::index() filter isOrmawa() sama seperti isMahasiswa()
10. Upload LPJ mahasiswa gagal load proposal → fixed: JS pakai status=Approved (bukan Disetujui) karena API hanya terima enum English
11. Template dokumen tidak bisa di-download/dimuat → fixed: web route proxy via ApiService (token server-side) + server-side rendering, hindari race condition token client-side axios
12. Verifikasi ajuan dana gagal saat upload bukti ("Status wajib diisi") → fixed: PHP tidak parse multipart pada PATCH; saat ada file gunakan POST + _method=PATCH (method spoofing)
13. CORS middleware crash pada download ("undefined method StreamedResponse::header()") → fixed: pakai $response->headers->set() bukan $response->header()
14. Lapor prestasi gagal ("Data truncated status_verifikasi") → fixed: migration normalize enum prestasi.status_verifikasi ke Indonesia ('Menunggu','Valid','Tidak Valid','Revisi') agar cocok dengan kode aplikasi

## Status Enum per Tabel (PENTING — jangan campur English/Indonesia)
- `proposal_kegiatan.status` & `proposal_prestasi_mahasiswa.status`: ENGLISH → `Pending, Revision, Approved, Rejected` (+ Cek LPJ, Revisi LPJ, Selesai untuk fase LPJ). Frontend map ke Indonesia untuk tampilan.
- `lpj_kegiatan.status_lpj`: INDONESIA → `Menunggu, Revisi, Disetujui`
- `prestasi.status_verifikasi`: INDONESIA → `Menunggu, Valid, Tidak Valid, Revisi`
- Saat insert/verify, selalu cek enum kolom target di migration sebelum pakai nilai status.

## Pola Penting: Download File & Auth Token
- API routes pakai `auth:sanctum` (Bearer token). Link `<a href>` browser TIDAK membawa token → gagal auth.
- SOLUSI: untuk download/list yang butuh data API dari halaman Blade, buat WEB ROUTE yang pakai ApiService (token di-attach server-side via session), bukan akses `/api/v1/...` langsung dari frontend.
- Untuk upload file ke endpoint PATCH/PUT via HTTP client: PHP tidak parse multipart pada PATCH/PUT. Gunakan POST + `_method=PATCH/PUT` (method spoofing) agar field & file terbaca.

## File Penting
- `config/permissions.php` - default permissions per role
- `app/Services/ApiService.php` - HTTP client untuk internal API calls dari web routes
- `routes/web.php` - semua route web (termasuk API proxy ke internal API)
- `routes/api.php` - semua REST API endpoints
- `resources/views/partials/ormawa_layout.blade.php` - layout + sidebar untuk Ormawa/Mahasiswa
- `resources/views/partials/kema_layout.blade.php` - layout + sidebar untuk Admin
- `resources/views/admin/kontrol_akun.blade.php` - halaman manajemen permission per user/ormawa
- `migration_frontend.md` - rencana migrasi ke React

## Kontrol Akun (Permission Management)
- Halaman: `/admin/kontrol-akun`
- Kemahasiswaan dapat toggle permission per ormawa (ormawa_name sebagai key)
- Setelah simpan, permissions di-sync via API `PATCH /users/{id}/permissions`
- Perubahan langsung berdampak ke fitur yang terlihat di dashboard user tersebut
