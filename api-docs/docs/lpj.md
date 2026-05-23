## 6. LPJ Kegiatan Endpoints

LPJ diunggah untuk proposal yang telah berstatus `Approved`.

### Daftar & Detail LPJ
- **List:** `GET /api/v1/lpj`
  - Query param `status_lpj`: `Pending`, `Revision`, `Approved`
- **Detail:** `GET /api/v1/lpj/{id}`

### Upload LPJ Baru
**Endpoint:** `POST /api/v1/lpj`
**Request Body (multipart/form-data):**
- `id_proposal` (Integer, referensi ke proposal berstatus `Approved`)
- `file_lpj` (PDF, max 5MB — divalidasi MIME type dan magic bytes `%PDF`)
- `tanggal_upload` (YYYY-MM-DD)

### Upload Revisi LPJ
**Endpoint:** `POST /api/v1/lpj/{id}/revisi`
**Request Body:**
- `file_lpj` (PDF, max 5MB)
- `tanggal_upload` (YYYY-MM-DD)

### Verifikasi LPJ (Admin Only)
**Endpoint:** `PATCH /api/v1/lpj/{id}/verifikasi`
**Request Body:**
- `status_lpj`: `Approved`, `Revision`

---