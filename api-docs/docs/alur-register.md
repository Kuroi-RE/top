## Alur Register & Role Assignment

### Skenario 1: User Baru Register sebagai Mahasiswa

```
1. User buka website → Klik "Register"
2. Input data & email institusi → POST /api/v1/auth/register
   (hanya @telkomuniversity.ac.id atau @ittelkom-pwt.ac.id)
3. Server: Create user dengan is_active=false, kirim email verifikasi
4. User cek email → klik link atau copy token
5. User submit token → POST /api/v1/auth/verify-email
6. Akun aktif → User bisa login
7. User bisa akses fitur Mahasiswa (upload prestasi, lihat informasi)
```

> **Catatan:** Jika email verifikasi tidak diterima, gunakan endpoint resend:
> `POST /api/v1/auth/resend-verification` (maks 3x per 60 menit)

---

### Skenario 2: Ormawa Institusi (UKM) Register & Assignment

```
Step 1: UKM Register & Verifikasi Email
   - UKM input data → POST /api/v1/auth/register
   - Server: Create user dengan is_active=false
   - UKM verifikasi email → POST /api/v1/auth/verify-email
   - Akun aktif dengan default role "Mahasiswa"

Step 2: Kemahasiswaan Filter & Assign
   - Kemahasiswaan: GET /api/v1/users?role=Mahasiswa
   - Kemahasiswaan: PATCH /api/v1/users/{id}/assign-role
     {
       "role": "Ormawa",
       "ormawa_type": "institusi",
       "ormawa_name": "BEMF"
     }

Step 3: UKM Sekarang Bisa
   - Upload proposal kegiatan
   - Upload LPJ
   - Lihat informasi Ormawa
```

---

### Skenario 3: Ormawa Prodi (Himpunan) Register & Assignment

```
Step 1: Himpunan Register & Verifikasi Email
   - Himpunan input data → POST /api/v1/auth/register
   - Server: Create user dengan is_active=false
   - Himpunan verifikasi email → POST /api/v1/auth/verify-email
   - Akun aktif dengan default role "Mahasiswa"

Step 2: Kemahasiswaan Filter & Assign
   - Kemahasiswaan: GET /api/v1/users?role=Mahasiswa
   - Kemahasiswaan: PATCH /api/v1/users/{id}/assign-role
     {
       "role": "Ormawa",
       "ormawa_type": "prodi",
       "ormawa_name": "HIMA TIF"
     }

Step 3: Himpunan Sekarang Bisa
   - Upload proposal kegiatan (Prodi level)
   - Upload LPJ Prodi
   - Lihat informasi Himpunan
```

---

### Skenario 4: Nonaktifkan User

```
1. Super Admin/Kemahasiswaan: GET /api/v1/users
2. Admin klik "Nonaktifkan" → PATCH /api/v1/users/{id}/toggle-akses
3. User tidak bisa login sampai di-aktifkan kembali
   (Login akan mengembalikan 403: "Akun Anda telah dinonaktifkan")
```

---