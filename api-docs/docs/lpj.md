## 6. LPJ Kegiatan Endpoints

LPJ diunggah untuk proposal yang telah berstatus Disetujui.

### Daftar & Detail LPJ
- **List:** `GET /api/v1/lpj`
- **Detail:** `GET /api/v1/lpj/{id}`

### Upload LPJ Baru
**Endpoint:** `POST /api/v1/lpj`
**Request Body (multipart/form-data):**
- `id_proposal` (Integer, referensi ke proposal disetujui)
- `file_lpj` (PDF, max 5MB)
- `tanggal_upload` (YYYY-MM-DD)

### Upload Revisi LPJ
**Endpoint:** `POST /api/v1/lpj/{id}/revisi`
**Request Body:**
- `file_lpj` (PDF, max 5MB)
- `tanggal_upload` (YYYY-MM-DD)

### Verifikasi LPJ (Admin Only)
**Endpoint:** `PATCH /api/v1/lpj/{id}/verifikasi`
**Request Body:**
- `status_lpj`: Disetujui, Revisi

---