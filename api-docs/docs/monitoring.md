## 7. Monitoring Anggaran & Prestasi Endpoints

Fitur khusus untuk DPMBEM dan Admin untuk memonitoring kegiatan dan prestasi seluruh Ormawa serta mahasiswa.

- **Daftar Seluruh Kegiatan:** `GET /api/v1/monitoring/kegiatan`
- **Transparansi Anggaran:** `GET /api/v1/monitoring/anggaran` (melihat jumlah dana diajukan vs disetujui per triwulan)
- **Daftar LPJ Ormawa:** `GET /api/v1/monitoring/lpj`
- **Detail Kegiatan (termasuk LPJ/Revisi):** `GET /api/v1/monitoring/kegiatan/{id}`
- **Statistik Sistem:** `GET /api/v1/monitoring/statistics`
- **Statistik Prestasi Mahasiswa:** `GET /api/v1/monitoring/prestasi` (mendapatkan agregasi data prestasi)
- **Export PDF Anggaran:** `GET /api/v1/monitoring/anggaran/export-pdf` (mengunduh PDF tabel rekapitulasi anggaran keseluruhan)
- **Export PDF Beranda Ormawa:** `GET /api/v1/monitoring/beranda_ormawa/export-pdf` (mengunduh PDF daftar semua kegiatan proposal ormawa)
- **Export PDF Prestasi Mahasiswa/Ormawa:** `GET /api/v1/prestasi/export-pdf` (mendukung query parameter `mewakili_ormawa`, `tingkat`, dan `search`)

### Filter pada Daftar Kegiatan

`GET /api/v1/monitoring/kegiatan` mendukung query parameters:

| Parameter      | Type   | Deskripsi                                    |
|----------------|--------|----------------------------------------------|
| status         | string | Filter status proposal                       |
| ajuan_triwulan | string | Filter triwulan (I, II, III, IV)             |
| ormawa_name    | string | Filter berdasarkan nama ormawa (exact match) |
| ormawa_type    | string | Filter berdasarkan jenis ormawa (institusi/prodi) |
| by_ormawa      | int    | Filter berdasarkan ID Ormawa                 |
| per_page       | int    | Jumlah per halaman (default: 20)             |

### Statistik Prestasi Mahasiswa

`GET /api/v1/monitoring/prestasi`

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Statistik prestasi mahasiswa",
  "data": {
    "total": 24,
    "valid": 18,
    "pending": 4,
    "revision_needed": 2,
    "by_tingkat": {
      "Internasional": 5,
      "Nasional": 12,
      "Regional": 7
    },
    "by_kategori": {
      "Sains": 10,
      "Olahraga": 8,
      "Seni": 6
    }
  }
}
```

---