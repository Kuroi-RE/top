## Alur Register & Role Assignment

### Skenario 1: User Baru Register sebagai Mahasiswa

```
1. User buka website → Klik "Register"
2. Input email & password → POST /api/v1/auth/register
3. Server: Create user dengan default role "Mahasiswa"
4. User dapat token dan langsung login
5. User bisa akses fitur Mahasiswa (upload prestasi, lihat informasi)
```

---

### Skenario 2: Ormawa Institusi (UKM) Register & Assignment

```
Step 1: UKM Register
   - UKM input email/password → POST /api/v1/auth/register
   - Server: Create user dengan default role "Mahasiswa"

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
Step 1: Himpunan Register
   - Himpunan input email/password → POST /api/v1/auth/register
   - Server: Create user dengan default role "Mahasiswa"

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
```

---