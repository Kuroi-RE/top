## 7. Publikasi Kegiatan Endpoints

Ormawa dapat mempublikasikan poster/informasi kegiatan yang perlu diverifikasi oleh admin (Kemahasiswaan).

> **Catatan:** Publikasi Kegiatan adalah entitas **berbeda** dari Informasi Kegiatan. Informasi adalah pengumuman dari admin, sedangkan Publikasi adalah konten poster dari Ormawa yang perlu approval.

---

### Daftar Publikasi

**Endpoint:** `GET /api/v1/publikasi`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status`: Filter berdasarkan status (`Pending`, `Approved`, `Rejected`, `Revision`)
- `per_page`: Jumlah data per halaman (default: 15)

**Akses:**
- Ormawa: hanya melihat publikasi milik sendiri
- Admin/Kemahasiswaan: melihat semua publikasi

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar publikasi kegiatan",
    "data": [
        {
            "id_publikasi": 1,
            "id_user": 4,
            "judul": "Workshop AI 2024",
            "ormawa": "BEMF",
            "caption": "Workshop kecerdasan buatan untuk mahasiswa...",
            "link": "https://example.com/event",
            "poster_url": "http://localhost:8000/storage/posters/abc123.jpg",
            "status": "Pending",
            "catatan_admin": null,
            "placement": null,
            "created_at": "2024-01-15T10:00:00.000000Z",
            "updated_at": "2024-01-15T10:00:00.000000Z",
            "user": {
                "id_user": 4,
                "username": "ukm1",
                "nama_depan": "BEMF",
                "nama_belakang": "UKM"
            }
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

### Buat Publikasi Baru

**Endpoint:** `POST /api/v1/publikasi`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Ormawa only)

**Request Body (multipart/form-data):**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| judul | string | Yes | Judul publikasi (max 255) |
| ormawa | string | Yes | Nama ormawa (max 255) |
| caption | string | Yes | Deskripsi/caption publikasi |
| link | string | No | URL terkait (valid URL, max 500) |
| poster | file | Yes | File poster (JPG/JPEG/PNG/WebP, max 5MB) |

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Publikasi kegiatan berhasil dibuat",
    "data": {
        "id_publikasi": 10,
        "judul": "Workshop AI 2024",
        "ormawa": "BEMF",
        "caption": "Workshop kecerdasan buatan...",
        "link": "https://example.com/event",
        "poster_url": "http://localhost:8000/storage/posters/abc123.jpg",
        "status": "Pending",
        "catatan_admin": null,
        "placement": null,
        "created_at": "2024-01-15T10:00:00.000000Z",
        "user": {...}
    }
}
```

---

### Detail Publikasi

**Endpoint:** `GET /api/v1/publikasi/{id}`

**Headers:** `Authorization: Bearer {token}`

**Akses:** Ormawa hanya bisa lihat milik sendiri, Admin bisa lihat semua.

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Detail publikasi kegiatan",
    "data": {...}
}
```

---

### Update Publikasi

**Endpoint:** `POST /api/v1/publikasi/{id}`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Ormawa pemilik)

> **Catatan:** Menggunakan `POST` (bukan `PUT`) karena mendukung upload file multipart.

**Request Body (multipart/form-data):**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| judul | string | No | Judul publikasi (max 255) |
| ormawa | string | No | Nama ormawa (max 255) |
| caption | string | No | Deskripsi/caption |
| link | string | No | URL terkait (nullable) |
| poster | file | No | File poster baru (JPG/JPEG/PNG/WebP, max 5MB) |

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Publikasi kegiatan berhasil diperbarui",
    "data": {...}
}
```

---

### Hapus Publikasi

**Endpoint:** `DELETE /api/v1/publikasi/{id}`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Ormawa pemilik atau Admin)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Publikasi kegiatan berhasil dihapus"
}
```

---

### Verifikasi Publikasi (Admin Only)

**Endpoint:** `PATCH /api/v1/publikasi/{id}/verifikasi`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Kemahasiswaan / Super Admin)

**Request Body:**

```json
{
    "status": "Approved",
    "catatan_admin": "Poster sudah sesuai",
    "placement": "beranda"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| status | string | Yes | `Approved`, `Rejected`, atau `Revision` |
| catatan_admin | string | No | Catatan dari admin |
| placement | string | No | Penempatan poster (jika Approved), max 100 |

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Verifikasi publikasi berhasil",
    "data": {
        "id_publikasi": 10,
        "status": "Approved",
        "catatan_admin": "Poster sudah sesuai",
        "placement": "beranda",
        ...
    }
}
```

---
