## 7. Monitoring Anggaran Endpoints

Fitur khusus untuk DPMBEM dan Admin untuk memonitoring kegiatan seluruh Ormawa.

- **Daftar Seluruh Kegiatan:** `GET /api/v1/monitoring/kegiatan`
- **Transparansi Anggaran:** `GET /api/v1/monitoring/anggaran` (melihat jumlah dana diajukan vs disetujui per triwulan)
- **Daftar LPJ Ormawa:** `GET /api/v1/monitoring/lpj`
- **Detail Kegiatan (termasuk LPJ/Revisi):** `GET /api/v1/monitoring/kegiatan/{id}`
- **Statistik Sistem:** `GET /api/v1/monitoring/statistics`

### Filter pada Daftar Kegiatan

`GET /api/v1/monitoring/kegiatan` mendukung query parameters:

| Parameter      | Type   | Deskripsi                                    |
|----------------|--------|----------------------------------------------|
| status         | string | Filter status proposal                       |
| ajuan_triwulan | string | Filter triwulan (I, II, III, IV)             |
| ormawa_name    | string | Filter berdasarkan nama ormawa (exact match) |
| ormawa_type    | string | Filter berdasarkan jenis ormawa (institusi/prodi) |
| per_page       | int    | Jumlah per halaman (default: 20)             |

---