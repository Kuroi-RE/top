## 8. DPMBEM Role & Monitoring Flow

Dewan Perwakilan Mahasiswa Badan Eksekutif Mahasiswa (DPMBEM) memiliki peran khusus sebagai **Monitor / Pengawas** yang memiliki visibilitas penuh terhadap data kegiatan, anggaran, dan prestasi mahasiswa tanpa hak verifikasi/modifikasi (Read-Only).

---

### Alur Kerja DPMBEM

1. **Autentikasi & Login:** DPMBEM login melalui halaman `/login` dan otomatis diarahkan ke `admin.beranda_dpmbem` (`GET /admin/beranda-dpmbem`).
2. **Pemantauan Kegiatan (Read-Only):** DPMBEM dapat melihat daftar kegiatan Ormawa dan status verifikasinya. Detail kegiatan dapat dibuka secara lengkap termasuk isi proposal, LPJ, dan riwayat revisi.
3. **Pemantauan Transparansi Anggaran:** DPMBEM memantau perbandingan besar dana yang diajukan vs disetujui per triwulan dan secara kumulatif.
4. **Pemantauan Prestasi Mahasiswa:** DPMBEM memiliki akses melihat grafik dan data agregasi prestasi mahasiswa (Nasional, Internasional, Regional) secara real-time.

---

### Endpoints Utama DPMBEM

Semua request wajib menyertakan token autentikasi di header:  
`Authorization: Bearer {token}`

#### 1. Statistik Kumulatif Sistem
`GET /api/v1/monitoring/statistics`

Mendapatkan ringkasan proposal, LPJ, dan anggaran kumulatif.

#### 2. Daftar Kegiatan Ormawa
`GET /api/v1/monitoring/kegiatan`

Mendapatkan daftar seluruh proposal kegiatan yang diajukan.  
Dapat difilter menggunakan parameter: `status`, `ajuan_triwulan`, `ormawa_name`, `ormawa_type`, `by_ormawa`.

#### 3. Detail Kegiatan & LPJ
`GET /api/v1/monitoring/kegiatan/{id}`

Mendapatkan detail proposal kegiatan beserta berkas LPJ dan riwayat revisinya.

#### 4. Transparansi Anggaran
`GET /api/v1/monitoring/anggaran`

Mendapatkan data transparansi anggaran per triwulan dan ringkasan persentase persetujuan.

#### 5. Statistik Prestasi Mahasiswa
`GET /api/v1/monitoring/prestasi`

Mendapatkan data statistik prestasi mahasiswa, termasuk jumlah berdasarkan tingkat kompetisi dan kategori.

---
