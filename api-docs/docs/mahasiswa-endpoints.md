## Mahasiswa API Endpoints

### Complete API Reference for Role Mahasiswa

Role Mahasiswa adalah role default untuk user baru yang mendaftar. Berikut adalah semua endpoints yang bisa diakses oleh role Mahasiswa:

---

### 1. Authentication Endpoints

#### Register (Public)

**Endpoint:** `POST /api/v1/auth/register`

**Headers:** Tidak perlu token

**Request Body:**

```json
{
    "nim": "12345678901",
    "nama_depan": "John",
    "nama_belakang": "Doe",
    "prodi": "Teknik Informatika",
    "email": "john123@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id_user": 21,
            "username": "john123",
            "nim": "12345678901",
            "nama_depan": "John",
            "nama_belakang": "Doe",
            "prodi": "Teknik Informatika",
            "email": "john123@example.com",
            "role": "Mahasiswa",
            "is_active": true,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|eyJ0eXAiOiJKV1QiLCJhbGc..."
    }
}
```

---

#### Login

**Endpoint:** `POST /api/v1/auth/login`

**Headers:** Tidak perlu token

**Request Body:**

```json
{
    "username": "john123",
    "password": "password123"
}
```

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "user": {
            "id_user": 21,
            "username": "john123",
            "email": "john123@example.com",
            "role": "Mahasiswa",
            "is_active": true,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|eyJ0eXAiOiJKV1QiLCJhbGc..."
    }
}
```

---

#### Logout

**Endpoint:** `POST /api/v1/auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Logout berhasil"
}
```

---

#### Get Current User

**Endpoint:** `GET /api/v1/auth/me`

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Data pengguna",
    "data": {
        "id_user": 21,
        "username": "john123",
        "email": "john123@example.com",
        "nim": "12345678901",
        "nama_depan": "John",
        "nama_belakang": "Doe",
        "prodi": "Teknik Informatika",
        "role": "Mahasiswa",
        "is_active": true,
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 2. Prestasi Endpoints

Mahasiswa dapat mengelola prestasi (penghargaan, kompetisi) mereka.

#### List Prestasi Milik Sendiri

**Endpoint:** `GET /api/v1/prestasi`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**

- `status_verifikasi`: Filter berdasarkan status (Menunggu, Revisi, Valid, Tidak Valid)
- `per_page`: Jumlah data per halaman (default: 15)

**Deskripsi:** Mahasiswa hanya bisa melihat prestasi milik mereka sendiri. Admin (Kemahasiswaan) dapat melihat semua prestasi.

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar prestasi",
    "data": [
        {
            "id_prestasi": 1,
            "nama_kompetisi": "Kompetisi Robot",
            "penyelenggara": "Telkom University",
            "tingkat": "Nasional",
            "capaian": "Juara 1",
            "kategori": "Kelompok",
            "status_verifikasi": "Valid",
            "user": {
                "id_user": 21,
                "username": "john123",
                "nama_depan": "John",
                "nama_belakang": "Doe"
            },
            "dokumen": [
                {
                    "id_dokumen": 1,
                    "jenis_dokumen": "Sertifikat",
                    "file": "/storage/prestasi/..."
                }
            ],
            "anggota": [
                {
                    "id_anggota": 1,
                    "nama": "Jane Doe",
                    "nim": "12345678902",
                    "prodi": "Teknik Informatika"
                }
            ],
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "pagination": {
        "total": 5,
        "per_page": 15,
        "current_page": 1,
        "total_pages": 1
    }
}
```

---

#### Buat Prestasi Baru

**Endpoint:** `POST /api/v1/prestasi`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Mahasiswa only)

**Request Body (multipart/form-data):**

```json
{
    "nama_kompetisi": "Kompetisi Robot",
    "penyelenggara": "Telkom University",
    "tingkat": "Nasional",
    "capaian": "Juara 1",
    "kategori": "Kelompok",
    "dokumen[0][jenis_dokumen]": "Sertifikat",
    "dokumen[0][file]": "file.pdf",
    "dokumen[1][jenis_dokumen]": "Piala",
    "dokumen[1][file]": "piala.jpg"
}
```

**Request Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_kompetisi | string | Yes | Nama kompetisi (max 255) |
| penyelenggara | string | Yes | Organisasi penyelenggara (max 255) |
| tingkat | string | Yes | Level kompetisi: Regional, Nasional, Internasional |
| capaian | string | Yes | Pencapaian/juara, misal: Juara 1, Juara 2, Top 10 |
| kategori | string | Yes | Tipe: Individu atau Kelompok |
| dokumen | array | Yes | Minimal 1 dokumen |
| dokumen[*][jenis_dokumen] | string | Yes | Jenis dokumen (Sertifikat, Piala, dll) |
| dokumen[*][file] | file | Yes | File dokumen (PDF/JPG/PNG, maksimal 5MB) |

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Prestasi berhasil dibuat",
    "data": {
        "id_prestasi": 10,
        "nama_kompetisi": "Kompetisi Robot",
        "penyelenggara": "Telkom University",
        "tingkat": "Nasional",
        "capaian": "Juara 1",
        "kategori": "Kelompok",
        "status_verifikasi": "Menunggu",
        "user": {
            "id_user": 21,
            "username": "john123"
        },
        "dokumen": [
            {
                "id_dokumen": 1,
                "jenis_dokumen": "Sertifikat",
                "file": "/storage/prestasi/..."
            }
        ],
        "anggota": [],
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

#### Detail Prestasi

**Endpoint:** `GET /api/v1/prestasi/{id}`

**Headers:** `Authorization: Bearer {token}`

**URL Parameters:**

- `id`: ID Prestasi (path parameter)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Detail prestasi",
    "data": {
        "id_prestasi": 10,
        "nama_kompetisi": "Kompetisi Robot",
        "penyelenggara": "Telkom University",
        "tingkat": "Nasional",
        "capaian": "Juara 1",
        "kategori": "Kelompok",
        "status_verifikasi": "Valid",
        "user": {
            "id_user": 21,
            "username": "john123",
            "nama_depan": "John",
            "nama_belakang": "Doe"
        },
        "dokumen": [
            {
                "id_dokumen": 1,
                "jenis_dokumen": "Sertifikat",
                "file": "/storage/prestasi/..."
            }
        ],
        "anggota": [
            {
                "id_anggota": 1,
                "nama": "Jane Doe",
                "nim": "12345678902",
                "prodi": "Teknik Informatika"
            }
        ],
        "dosen": [
            {
                "id_dosen": 1,
                "nama": "Dr. Ahmad",
                "nip": "19800101200001",
                "surat_tugas": "/storage/dosen/..."
            }
        ],
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-05T00:00:00.000000Z"
    }
}
```

---

#### Cek Status Verifikasi Prestasi

**Endpoint:** `GET /api/v1/prestasi/{id}/status`

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Status prestasi",
    "data": {
        "id_prestasi": 10,
        "status_verifikasi": "Valid"
    }
}
```

---

#### Update Prestasi

**Endpoint:** `PUT /api/v1/prestasi/{id}`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Mahasiswa - hanya pemilik)

**Deskripsi:** Mahasiswa hanya bisa update jika status masih "Menunggu" atau "Revisi".

**Request Body:**

```json
{
    "nama_kompetisi": "Kompetisi Robot Update",
    "penyelenggara": "Telkom University Update",
    "tingkat": "Internasional",
    "capaian": "Juara 2",
    "kategori": "Kelompok"
}
```

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Prestasi berhasil diperbarui",
    "data": {
        "id_prestasi": 10,
        "nama_kompetisi": "Kompetisi Robot Update",
        "penyelenggara": "Telkom University Update",
        "tingkat": "Internasional",
        "capaian": "Juara 2",
        "kategori": "Kelompok",
        "status_verifikasi": "Menunggu",
        "updated_at": "2024-01-05T00:00:00.000000Z"
    }
}
```

---

#### Tambah Anggota Tim Prestasi

**Endpoint:** `POST /api/v1/prestasi/{id}/anggota`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Mahasiswa - hanya pemilik)

**Deskripsi:** Hanya bisa ditambahkan untuk prestasi dengan kategori "Kelompok".

**Request Body:**

```json
{
    "nama": "Jane Doe",
    "nim": "12345678902",
    "prodi": "Teknik Informatika"
}
```

**Request Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama | string | Yes | Nama anggota tim (max 100) |
| nim | string | Yes | NIM anggota (max 12) |
| prodi | string | Yes | Program studi anggota (max 100) |

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Anggota tim berhasil ditambahkan",
    "data": {
        "id_anggota": 1,
        "id_prestasi": 10,
        "nama": "Jane Doe",
        "nim": "12345678902",
        "prodi": "Teknik Informatika"
    }
}
```

---

#### Tambah Dosen Pembimbing

**Endpoint:** `POST /api/v1/prestasi/{id}/dosen`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Mahasiswa - hanya pemilik)

**Request Body (multipart/form-data):**

```json
{
    "nama": "Dr. Ahmad",
    "nip": "19800101200001",
    "surat_tugas": "file.pdf"
}
```

**Request Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama | string | Yes | Nama dosen (max 100) |
| nip | string | Yes | NIP dosen (max 20) |
| surat_tugas | file | Yes | File surat tugas (PDF, maksimal 5MB) |

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Dosen pembimbing berhasil ditambahkan",
    "data": {
        "id_dosen": 1,
        "id_prestasi": 10,
        "nama": "Dr. Ahmad",
        "nip": "19800101200001",
        "surat_tugas": "/storage/dosen/..."
    }
}
```

---

#### Hapus Anggota Tim

**Endpoint:** `DELETE /api/v1/prestasi/{id}/anggota/{anggota_id}`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Mahasiswa - hanya pemilik)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Anggota tim berhasil dihapus"
}
```

---

#### Hapus Dosen Pembimbing

**Endpoint:** `DELETE /api/v1/prestasi/{id}/dosen/{dosen_id}`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Mahasiswa - hanya pemilik)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Dosen pembimbing berhasil dihapus"
}
```

---

#### Hapus Prestasi

**Endpoint:** `DELETE /api/v1/prestasi/{id}`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Mahasiswa - hanya pemilik)

**Deskripsi:** Mahasiswa hanya bisa hapus jika status prestasi masih "Menunggu". File dokumen akan otomatis dihapus dari storage.

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Prestasi berhasil dihapus"
}
```

**Response (Error - 422):**

```json
{
    "status": "error",
    "message": "Prestasi yang sudah diverifikasi tidak dapat dihapus"
}
```

---

### 3. Template Dokumen Endpoints

Mahasiswa dapat mengakses template dokumen untuk referensi.

#### List Template Dokumen

**Endpoint:** `GET /api/v1/template`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**

- `jenis_template`: Filter berdasarkan jenis template
- `per_page`: Jumlah data per halaman (default: 15)

**Deskripsi:** Semua pengguna (termasuk Mahasiswa) dapat melihat daftar template.

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar template dokumen",
    "data": [
        {
            "id_template": 1,
            "nama_template": "Formulir Prestasi",
            "jenis_template": "Formulir",
            "file": "/storage/templates/...",
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        {
            "id_template": 2,
            "nama_template": "Surat Keterangan Prestasi",
            "jenis_template": "Surat",
            "file": "/storage/templates/...",
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "pagination": {
        "total": 10,
        "per_page": 15,
        "current_page": 1,
        "total_pages": 1
    }
}
```

---

#### Detail Template Dokumen

**Endpoint:** `GET /api/v1/template/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Detail template dokumen",
    "data": {
        "id_template": 1,
        "nama_template": "Formulir Prestasi",
        "jenis_template": "Formulir",
        "file": "/storage/templates/...",
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

#### Download Template Dokumen

**Endpoint:** `GET /api/v1/template/{id}/download`

**Headers:** `Authorization: Bearer {token}`

**Deskripsi:** Download file template dalam format yang sesuai (biasanya PDF).

**Response:** File binary (PDF)

---

### 4. Informasi Kegiatan Endpoints

Mahasiswa dapat melihat informasi dan pengumuman dari Kemahasiswaan dan Ormawa.

#### List Informasi Kegiatan

**Endpoint:** `GET /api/v1/informasi`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**

- `role`: Filter berdasarkan target role (Ormawa, Kemahasiswaan)
- `per_page`: Jumlah data per halaman (default: 15)

**Deskripsi:** Semua pengguna dapat melihat informasi. Info ditampilkan berdasarkan tanggal paling baru.

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar informasi kegiatan",
    "data": [
        {
            "id_informasi": 1,
            "judul": "Pengumuman Kompetisi 2024",
            "role": "Kemahasiswaan",
            "caption": "Kompetisi tahun ini akan diadakan pada bulan Maret...",
            "file": "/storage/informasi/...",
            "user": {
                "id_user": 2,
                "username": "kemahasiswaan",
                "nama_depan": "Dinas",
                "nama_belakang": "Kemahasiswaan"
            },
            "created_at": "2024-01-15T00:00:00.000000Z"
        },
        {
            "id_informasi": 2,
            "judul": "Kegiatan BEMF Bulan Januari",
            "role": "Ormawa",
            "caption": "BEMF akan mengadakan rapat rutin pada...",
            "file": null,
            "user": {
                "id_user": 4,
                "username": "ukm1",
                "nama_depan": "BEMF",
                "nama_belakang": "UKM"
            },
            "created_at": "2024-01-14T00:00:00.000000Z"
        }
    ],
    "pagination": {
        "total": 15,
        "per_page": 15,
        "current_page": 1,
        "total_pages": 1
    }
}
```

---

#### Detail Informasi Kegiatan

**Endpoint:** `GET /api/v1/informasi/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Detail informasi kegiatan",
    "data": {
        "id_informasi": 1,
        "judul": "Pengumuman Kompetisi 2024",
        "role": "Kemahasiswaan",
        "caption": "Kompetisi tahun ini akan diadakan pada bulan Maret dengan tema...",
        "file": "/storage/informasi/kompetisi2024.pdf",
        "user": {
            "id_user": 2,
            "username": "kemahasiswaan",
            "nama_depan": "Dinas",
            "nama_belakang": "Kemahasiswaan"
        },
        "created_at": "2024-01-15T00:00:00.000000Z",
        "updated_at": "2024-01-15T00:00:00.000000Z"
    }
}
```

---