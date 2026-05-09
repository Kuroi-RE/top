## 5. Proposal Kegiatan Endpoints

Ormawa dapat mengajukan proposal kegiatan dan Kemahasiswaan dapat melakukan verifikasi.

### Daftar Proposal Kegiatan

**Endpoint:** `GET /api/v1/proposal`
**Headers:** `Authorization: Bearer {token}`
**Query Parameters:**
- `status`: Menunggu, Revisi, Disetujui, Ditolak
- `triwulan`: I, II, III, IV

**Response (Success - 200):**
```json
{
    "status": "success",
    "message": "Daftar proposal kegiatan",
    "data": [...]
}
```

### Ajukan Proposal Baru

**Endpoint:** `POST /api/v1/proposal`
**Headers:** `Authorization: Bearer {token}`
**Request Body (multipart/form-data):**
- `ajuan_triwulan` (I, II, III, IV)
- `risiko_proposal` (Rendah, Sedang, Tinggi)
- `nama_kegiatan` (max 150)
- `waktu_kegiatan` (YYYY-MM-DD)
- `tempat_kegiatan` (max 150)
- `besar_ajuan` (decimal)
- `nomor_rekening`, `nama_rekening`, `nama_bank`
- `honor_pelatih` (Ya/Tidak)
- `file` (PDF, max 5MB)

### Detail, Update, Hapus Proposal
- **Detail:** `GET /api/v1/proposal/{id}`
- **Update:** `PUT /api/v1/proposal/{id}` (hanya bila status Menunggu/Revisi)
- **Hapus:** `DELETE /api/v1/proposal/{id}`
- **Cek Status:** `GET /api/v1/proposal/{id}/status`

### Upload Revisi Proposal
**Endpoint:** `POST /api/v1/proposal/{id}/revisi`
Sama dengan pengajuan baru, namun di-submit saat diminta revisi dan wajib mengunggah file baru. Mengembalikan status proposal ke Menunggu.

### Verifikasi Proposal (Admin Only)
**Endpoint:** `PATCH /api/v1/proposal/{id}/verifikasi`
**Request Body:**
- `status`: Disetujui, Revisi, Ditolak
- `catatan_admin`: String (Opsional)
- `anggaran_disetujui`: Decimal (Wajib jika Disetujui)

---