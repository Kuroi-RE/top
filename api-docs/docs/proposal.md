## 5. Proposal Kegiatan Endpoints

Ormawa dapat mengajukan proposal kegiatan dan Kemahasiswaan dapat melakukan verifikasi.

---

### Daftar Proposal Kegiatan

**Endpoint:** `GET /api/v1/proposal`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status`: `Pending`, `Revision`, `Approved`, `Rejected`, `Cek LPJ`, `Selesai`, `Revisi LPJ`
- `triwulan`: I, II, III, IV
- `ormawa_id`: ID user / Ormawa
- `ormawa_name`: Pencarian parsial berdasarkan nama Ormawa

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar proposal kegiatan",
    "data": [
        {
            "id_proposal": 1,
            "id_user": 4,
            "user": {...},
            "ajuan_triwulan": "I",
            "risiko_proposal": "Sedang",
            "no_telepon": "081234567890",
            "nama_kegiatan": "Workshop AI",
            "waktu_kegiatan": "2024-03-15",
            "tempat_kegiatan": "Gedung A",
            "besar_ajuan": 5000000.00,
            "nomor_rekening": "1234567890",
            "nama_rekening": "BEMF",
            "nama_bank": "BNI",
            "honor_pelatih": "Ya",
            "file": "proposals/abc123.pdf",
            "status": "Pending",
            "anggaran_disetujui": null,
            "catatan_admin": null,
            "file_lpj_keuangan_url": null,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "pagination": {...}
}
```

---

### Ajukan Proposal Baru

**Endpoint:** `POST /api/v1/proposal`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Ormawa)

**Request Body (multipart/form-data):**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| ajuan_triwulan | string | Yes | I, II, III, IV |
| risiko_proposal | string | Yes | Rendah, Sedang, Tinggi |
| no_telepon | string | Yes | Max 15 karakter |
| nama_kegiatan | string | Yes | Max 150 karakter |
| waktu_kegiatan | date | Yes | YYYY-MM-DD |
| tempat_kegiatan | string | Yes | Max 150 karakter |
| besar_ajuan | decimal | Yes | Minimal 100000 |
| nomor_rekening | string | Yes | Max 30 karakter |
| nama_rekening | string | Yes | Max 100 karakter |
| nama_bank | string | Yes | Max 100 karakter |
| honor_pelatih | string | Yes | Ya / Tidak |
| file | file | Yes | PDF, max 5MB (divalidasi MIME + magic bytes) |

**Response (Success - 201):** Status awal = `Pending`

---

### Detail, Update, Hapus Proposal

- **Detail:** `GET /api/v1/proposal/{id}`
- **Update:** `PUT /api/v1/proposal/{id}` (hanya bila status `Pending` atau `Revision`)
- **Hapus:** `DELETE /api/v1/proposal/{id}` (tidak bisa jika status `Approved`)
- **Cek Status:** `GET /api/v1/proposal/{id}/status`

---

### Upload Revisi Proposal

**Endpoint:** `POST /api/v1/proposal/{id}/revisi`

Sama dengan pengajuan baru, namun di-submit saat diminta revisi. Mengembalikan status proposal ke `Pending`.

---

### Verifikasi Proposal (Admin Only)

**Endpoint:** `PATCH /api/v1/proposal/{id}/verifikasi`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Kemahasiswaan / Super Admin)

**Request Body (multipart/form-data):**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| status | string | Yes | `Approved`, `Revision`, `Rejected` |
| catatan_admin | string | No | Catatan untuk pemohon |
| anggaran_disetujui | decimal | Conditional | Wajib jika status `Approved` |
| file_lpj_keuangan | file | No | File LPJ keuangan (PDF, max 10MB) |

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Verifikasi proposal berhasil",
    "data": {
        "id_proposal": 1,
        "status": "Approved",
        "anggaran_disetujui": 4500000.00,
        "catatan_admin": "Disetujui dengan catatan...",
        "file_lpj_keuangan_url": "http://localhost:8000/storage/lpj_keuangan/abc.pdf",
        ...
    }
}
```

---

### Status Flow Proposal

```
Pending → Approved / Revision / Rejected
Revision → Pending (setelah submit revisi)
Approved → Cek LPJ (setelah LPJ diupload)
Cek LPJ → Selesai (LPJ disetujui) / Revisi LPJ (LPJ diminta revisi)
Revisi LPJ → Cek LPJ (setelah LPJ revisi diupload)
```

---
