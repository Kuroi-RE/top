/**
 * API Client Helper
 * Wrapper untuk axios dengan base URL http://localhost:8000/api/v1
 *
 * Dokumentasi lengkap tersedia di /api-docs/
 */

// ── Token Management ─────────────────────────────────────────────────────────
const TOKEN_KEY = 'topkema_api_token';

export const tokenManager = {
  set: (token) => {
    localStorage.setItem(TOKEN_KEY, token);
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  },

  get: () => localStorage.getItem(TOKEN_KEY),

  remove: () => {
    localStorage.removeItem(TOKEN_KEY);
    delete window.axios.defaults.headers.common['Authorization'];
  },

  /** Inisialisasi token dari localStorage saat halaman dimuat */
  init: () => {
    const token = localStorage.getItem(TOKEN_KEY);
    if (token) {
      window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
  },
};

// ── API Endpoints ─────────────────────────────────────────────────────────────
export const api = {

  // ── Authentication ─────────────────────────────────────────────────────────
  auth: {
    /**
     * Login
     * POST /api/v1/auth/login
     * @param {string} username  Username atau NIM atau email
     * @param {string} password
     */
    login: (username, password) =>
      window.axios.post('auth/login', { username, password }),

    /**
     * Register Mahasiswa baru (public)
     * POST /api/v1/auth/register
     * @param {Object} data { nim, nama_depan, nama_belakang, prodi, email, password, password_confirmation }
     */
    register: (data) =>
      window.axios.post('auth/register', data),

    /**
     * Logout — membutuhkan token Bearer
     * POST /api/v1/auth/logout
     */
    logout: () =>
      window.axios.post('auth/logout'),

    /**
     * Ambil data user yang sedang login
     * GET /api/v1/auth/me
     */
    me: () =>
      window.axios.get('auth/me'),
  },

  // ── User Management ─────────────────────────────────────────────────────────
  user: {
    /**
     * Daftar semua pengguna
     * GET /api/v1/users
     * @param {Object} params { role, is_active, per_page }
     */
    list: (params = {}) =>
      window.axios.get('users', { params }),

    /**
     * Assign role ke user
     * PATCH /api/v1/users/{id}/assign-role
     * @param {number} id
     * @param {Object} data { role, ormawa_type?, ormawa_name? }
     */
    assignRole: (id, data) =>
      window.axios.patch(`users/${id}/assign-role`, data),

    /**
     * Toggle aktif/nonaktif akun user
     * PATCH /api/v1/users/{id}/toggle-akses
     * @param {number} id
     */
    toggleAkses: (id) =>
      window.axios.patch(`users/${id}/toggle-akses`),

    /**
     * Lihat direct permissions user
     * GET /api/v1/users/{id}/permissions
     * @param {number} id
     */
    getPermissions: (id) =>
      window.axios.get(`users/${id}/permissions`),

    /**
     * Sync direct permissions user
     * PATCH /api/v1/users/{id}/permissions
     * @param {number} id
     * @param {string[]} permissions Array nama permissions
     */
    syncPermissions: (id, permissions) =>
      window.axios.patch(`users/${id}/permissions`, { permissions }),

    /**
     * Daftar semua roles (Spatie)
     * GET /api/v1/users/spatie/roles
     */
    getRoles: () =>
      window.axios.get('users/spatie/roles'),

    /**
     * Daftar semua permissions (Spatie)
     * GET /api/v1/users/spatie/permissions
     */
    getAllPermissions: () =>
      window.axios.get('users/spatie/permissions'),
  },

  // ── Proposal Kegiatan ───────────────────────────────────────────────────────
  proposal: {
    /**
     * Daftar proposal
     * GET /api/v1/proposal
     * @param {Object} params { status, triwulan }
     */
    list: (params = {}) =>
      window.axios.get('proposal', { params }),

    /**
     * Detail proposal
     * GET /api/v1/proposal/{id}
     */
    get: (id) =>
      window.axios.get(`proposal/${id}`),

    /**
     * Buat proposal baru (Ormawa only)
     * POST /api/v1/proposal  (multipart/form-data)
     * @param {FormData} formData
     */
    create: (formData) =>
      window.axios.post('proposal', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    /**
     * Update proposal
     * PUT /api/v1/proposal/{id}
     */
    update: (id, data) =>
      window.axios.put(`proposal/${id}`, data),

    /**
     * Hapus proposal
     * DELETE /api/v1/proposal/{id}
     */
    delete: (id) =>
      window.axios.delete(`proposal/${id}`),

    /**
     * Cek status proposal
     * GET /api/v1/proposal/{id}/status
     */
    checkStatus: (id) =>
      window.axios.get(`proposal/${id}/status`),

    /**
     * Upload revisi proposal
     * POST /api/v1/proposal/{id}/revisi  (multipart/form-data)
     * @param {FormData} formData
     */
    submitRevision: (id, formData) =>
      window.axios.post(`proposal/${id}/revisi`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    /**
     * Verifikasi proposal (Admin only)
     * PATCH /api/v1/proposal/{id}/verifikasi
     * @param {Object} data { status, catatan_admin?, anggaran_disetujui? }
     */
    verify: (id, data) =>
      window.axios.patch(`proposal/${id}/verifikasi`, data),
  },

  // ── LPJ Kegiatan ───────────────────────────────────────────────────────────
  lpj: {
    /**
     * Daftar LPJ
     * GET /api/v1/lpj
     */
    list: (params = {}) =>
      window.axios.get('lpj', { params }),

    /**
     * Detail LPJ
     * GET /api/v1/lpj/{id}
     */
    get: (id) =>
      window.axios.get(`lpj/${id}`),

    /**
     * Upload LPJ baru
     * POST /api/v1/lpj  (multipart/form-data)
     * @param {FormData} formData  { id_proposal, file_lpj, tanggal_upload }
     */
    create: (formData) =>
      window.axios.post('lpj', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    /**
     * Upload revisi LPJ
     * POST /api/v1/lpj/{id}/revisi  (multipart/form-data)
     * @param {FormData} formData  { file_lpj, tanggal_upload }
     */
    submitRevision: (id, formData) =>
      window.axios.post(`lpj/${id}/revisi`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    /**
     * Verifikasi LPJ (Admin only)
     * PATCH /api/v1/lpj/{id}/verifikasi
     * @param {Object} data { status_lpj: 'Disetujui' | 'Revisi' }
     */
    verify: (id, data) =>
      window.axios.patch(`lpj/${id}/verifikasi`, data),
  },

  // ── Prestasi ────────────────────────────────────────────────────────────────
  prestasi: {
    /**
     * Daftar prestasi (Mahasiswa: milik sendiri, Admin: semua)
     * GET /api/v1/prestasi
     * @param {Object} params { status_verifikasi, per_page }
     */
    list: (params = {}) =>
      window.axios.get('prestasi', { params }),

    /**
     * Detail prestasi
     * GET /api/v1/prestasi/{id}
     */
    get: (id) =>
      window.axios.get(`prestasi/${id}`),

    /**
     * Buat prestasi baru (Mahasiswa only)
     * POST /api/v1/prestasi  (multipart/form-data)
     * @param {FormData} formData
     */
    create: (formData) =>
      window.axios.post('prestasi', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    /**
     * Update prestasi (Mahasiswa — hanya saat status Menunggu/Revisi)
     * PUT /api/v1/prestasi/{id}
     */
    update: (id, data) =>
      window.axios.put(`prestasi/${id}`, data),

    /**
     * Hapus prestasi (Mahasiswa — hanya saat status Menunggu)
     * DELETE /api/v1/prestasi/{id}
     */
    delete: (id) =>
      window.axios.delete(`prestasi/${id}`),

    /**
     * Cek status verifikasi prestasi
     * GET /api/v1/prestasi/{id}/status
     */
    checkStatus: (id) =>
      window.axios.get(`prestasi/${id}/status`),

    /**
     * Verifikasi prestasi (Kemahasiswaan/Admin only)
     * PATCH /api/v1/prestasi/{id}/verifikasi
     * @param {Object} data { status_verifikasi: 'Valid' | 'Tidak Valid' | 'Revisi' }
     */
    verify: (id, data) =>
      window.axios.patch(`prestasi/${id}/verifikasi`, data),

    /**
     * Tambah anggota tim prestasi (Mahasiswa — kategori Kelompok)
     * POST /api/v1/prestasi/{id}/anggota
     * @param {Object} data { nama, nim, prodi }
     */
    addAnggota: (id, data) =>
      window.axios.post(`prestasi/${id}/anggota`, data),

    /**
     * Hapus anggota tim
     * DELETE /api/v1/prestasi/{id}/anggota/{anggota_id}
     */
    deleteAnggota: (id, anggotaId) =>
      window.axios.delete(`prestasi/${id}/anggota/${anggotaId}`),

    /**
     * Tambah dosen pembimbing
     * POST /api/v1/prestasi/{id}/dosen  (multipart/form-data)
     * @param {FormData} formData  { nama, nip, surat_tugas }
     */
    addDosen: (id, formData) =>
      window.axios.post(`prestasi/${id}/dosen`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    /**
     * Hapus dosen pembimbing
     * DELETE /api/v1/prestasi/{id}/dosen/{dosen_id}
     */
    deleteDosen: (id, dosenId) =>
      window.axios.delete(`prestasi/${id}/dosen/${dosenId}`),
  },

  // ── Template Dokumen ────────────────────────────────────────────────────────
  template: {
    /**
     * Daftar template dokumen
     * GET /api/v1/template
     * @param {Object} params { jenis_template, per_page }
     */
    list: (params = {}) =>
      window.axios.get('template', { params }),

    /**
     * Detail template
     * GET /api/v1/template/{id}
     */
    get: (id) =>
      window.axios.get(`template/${id}`),

    /**
     * Buat template baru (Admin only)
     * POST /api/v1/template  (multipart/form-data)
     */
    create: (formData) =>
      window.axios.post('template', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    /**
     * Update template (Admin only)
     * PUT /api/v1/template/{id}
     */
    update: (id, data) =>
      window.axios.put(`template/${id}`, data),

    /**
     * Hapus template (Admin only)
     * DELETE /api/v1/template/{id}
     */
    delete: (id) =>
      window.axios.delete(`template/${id}`),

    /**
     * Download template
     * GET /api/v1/template/{id}/download
     */
    download: (id) =>
      window.axios.get(`template/${id}/download`, { responseType: 'blob' }),
  },

  // ── Informasi Kegiatan ──────────────────────────────────────────────────────
  informasi: {
    /**
     * Daftar informasi/pengumuman kegiatan
     * GET /api/v1/informasi
     * @param {Object} params { role, per_page }
     */
    list: (params = {}) =>
      window.axios.get('informasi', { params }),

    /**
     * Detail informasi kegiatan
     * GET /api/v1/informasi/{id}
     */
    get: (id) =>
      window.axios.get(`informasi/${id}`),
  },

  // ── Monitoring ──────────────────────────────────────────────────────────────
  monitoring: {
    /**
     * Daftar seluruh kegiatan Ormawa
     * GET /api/v1/monitoring/kegiatan
     */
    kegiatan: (params = {}) =>
      window.axios.get('monitoring/kegiatan', { params }),

    /**
     * Transparansi anggaran per triwulan
     * GET /api/v1/monitoring/anggaran
     */
    anggaran: (params = {}) =>
      window.axios.get('monitoring/anggaran', { params }),

    /**
     * Daftar LPJ semua Ormawa
     * GET /api/v1/monitoring/lpj
     */
    lpj: (params = {}) =>
      window.axios.get('monitoring/lpj', { params }),

    /**
     * Detail kegiatan termasuk LPJ & revisi
     * GET /api/v1/monitoring/kegiatan/{id}
     */
    detailKegiatan: (id) =>
      window.axios.get(`monitoring/kegiatan/${id}`),

    /**
     * Statistik sistem (total proposal, prestasi, users, dll.)
     * GET /api/v1/monitoring/statistics
     */
    statistics: () =>
      window.axios.get('monitoring/statistics'),
  },
};

export default api;
