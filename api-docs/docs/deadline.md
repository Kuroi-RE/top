## 8. Deadline Management Endpoints

Endpoint untuk mengelola deadline pengumpulan proposal. Admin dapat membuat dan menghapus deadline, semua user authenticated dapat melihat deadline aktif.

---

### Lihat Deadline Aktif

**Endpoint:** `GET /api/v1/deadline`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (semua user authenticated)

**Deskripsi:** Mengembalikan deadline yang sedang aktif saat ini. Hanya ada satu deadline aktif pada satu waktu.

**Response (Success - 200, ada deadline aktif):**

```json
{
    "status": "success",
    "message": "Deadline aktif ditemukan",
    "data": {
        "id": 1,
        "title": "Deadline Proposal Triwulan I",
        "deadline_at": "2024-03-31T23:59:59.000000Z",
        "is_active": true,
        "sisa_hari": 15
    }
}
```

**Response (Success - 200, tidak ada deadline aktif):**

```json
{
    "status": "success",
    "message": "Tidak ada deadline aktif",
    "data": null
}
```

---

### Lihat Semua Deadline (Admin Only)

**Endpoint:** `GET /api/v1/deadline/all`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Kemahasiswaan / Super Admin)

**Deskripsi:** Menampilkan semua deadline (aktif maupun tidak), diurutkan dari yang terbaru.

**Response (Success - 200):**

```json
{
    "status": "success",
    "data": [
        {
            "id": 2,
            "title": "Deadline Proposal Triwulan II",
            "deadline_at": "2024-06-30T23:59:59.000000Z",
            "is_active": true,
            "created_at": "2024-04-01T00:00:00.000000Z",
            "updated_at": "2024-04-01T00:00:00.000000Z"
        },
        {
            "id": 1,
            "title": "Deadline Proposal Triwulan I",
            "deadline_at": "2024-03-31T23:59:59.000000Z",
            "is_active": false,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-04-01T00:00:00.000000Z"
        }
    ]
}
```

---

### Buat Deadline Baru (Admin Only)

**Endpoint:** `POST /api/v1/deadline`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Kemahasiswaan / Super Admin)

**Deskripsi:** Membuat deadline baru. Deadline aktif sebelumnya akan otomatis dinonaktifkan.

**Request Body:**

```json
{
    "title": "Deadline Proposal Triwulan II",
    "deadline_at": "2024-06-30 23:59:59"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| title | string | Yes | Judul deadline (max 255) |
| deadline_at | datetime | Yes | Tanggal deadline (harus setelah hari ini) |

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Deadline berhasil dibuat",
    "data": {
        "id": 2,
        "title": "Deadline Proposal Triwulan II",
        "deadline_at": "2024-06-30T23:59:59.000000Z",
        "is_active": true,
        "created_at": "2024-04-01T00:00:00.000000Z",
        "updated_at": "2024-04-01T00:00:00.000000Z"
    }
}
```

---

### Hapus Deadline (Admin Only)

**Endpoint:** `DELETE /api/v1/deadline/{id}`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Kemahasiswaan / Super Admin)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Deadline berhasil dihapus"
}
```

---
