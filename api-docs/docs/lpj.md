## 6. LPJ Kegiatan Endpoints

LPJ (Laporan Pertanggungjawaban) diunggah untuk proposal yang telah berstatus `Approved`.

---

### Daftar LPJ

**Endpoint:** `GET /api/v1/lpj`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status_lpj`: `Pending`, `Revision`, `Approved`
- `per_page`: Jumlah data per halaman (default: 15)

**Akses:**
- Ormawa/Mahasiswa: hanya melihat LPJ dari proposal milik sendiri
- Admin: melihat semua LPJ

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar LPJ kegiatan",
    "data": [...],
    "pagination": {...}
}
```

---

### Detail LPJ

**Endpoint:** `GET /api/v1/lpj/{id}`

**Headers:** `Authorization: Bearer {token}`

---

### Upload LPJ Baru

**Endpoint:** `POST /api/v1/lpj`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Ormawa / Mahasiswa)

**Request Body (multipart/form-data):**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_proposal | integer | Yes | ID proposal berstatus `Approved` atau `Revisi LPJ` |
| file_lpj | file | Yes | File LPJ (PDF, max 5MB) |
| tanggal_upload | date | No | Tanggal upload (default: hari ini) |

**Behavior:**
- Jika LPJ sudah pernah diupload untuk proposal ini, file lama akan **di-replace** (updateOrCreate)
- Status LPJ di-reset ke `Pending`
- Status proposal otomatis berubah ke `Cek LPJ`

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "LPJ kegiatan berhasil diupload",
    "data": {...}
}
```

**Response (Error - 422, proposal belum disetujui):**

```json
{
    "status": "error",
    "message": "LPJ hanya bisa diupload untuk proposal yang sudah disetujui atau revisi LPJ",
    "errors": {
        "proposal": "Status proposal harus Approved atau Revisi LPJ"
    }
}
```

---

### Upload Revisi LPJ

**Endpoint:** `POST /api/v1/lpj/{id}/revisi`

**Headers:** `Authorization: Bearer {token}`

**Request Body (multipart/form-data):**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| file_lpj | file | Yes | File LPJ revisi (PDF, max 5MB) |
| tanggal_upload | date | Yes | Tanggal upload revisi |

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Revisi LPJ berhasil diupload",
    "data": {...}
}
```

---

### Verifikasi LPJ (Admin Only)

**Endpoint:** `PATCH /api/v1/lpj/{id}/verifikasi`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Kemahasiswaan / Super Admin)

**Request Body:**

```json
{
    "status_lpj": "Approved",
    "catatan_admin": "LPJ sudah lengkap"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| status_lpj | string | Yes | `Approved` atau `Revision` |
| catatan_admin | string | No | Catatan dari admin |

**Auto-update Status Proposal:**
- Jika `status_lpj = Approved` → status proposal berubah ke `Selesai`
- Jika `status_lpj = Revision` → status proposal berubah ke `Revisi LPJ`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Verifikasi LPJ berhasil",
    "data": {...}
}
```

---
