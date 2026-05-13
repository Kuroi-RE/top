/**
 * API Client Helper
 * Simple wrapper untuk axios dengan base URL sudah dikonfigurasi
 */

export const api = {
  // Authentication
  auth: {
    login: (email, password) => 
      window.axios.post('auth/login', { email, password }),
    
    register: (data) => 
      window.axios.post('auth/register', data),
    
    logout: () => 
      window.axios.post('auth/logout'),
    
    me: () => 
      window.axios.get('auth/me'),
    
    generateToken: () => 
      window.axios.post('auth/generate-token'),
  },

  // Proposal
  proposal: {
    list: () => 
      window.axios.get('proposal'),
    
    get: (id) => 
      window.axios.get(`proposal/${id}`),
    
    create: (data) => 
      window.axios.post('proposal', data),
    
    update: (id, data) => 
      window.axios.put(`proposal/${id}`, data),
    
    delete: (id) => 
      window.axios.delete(`proposal/${id}`),
    
    checkStatus: (id) => 
      window.axios.get(`proposal/${id}/status`),
    
    submitRevision: (id, data) => 
      window.axios.post(`proposal/${id}/revisi`, data),
    
    verify: (id, data) => 
      window.axios.patch(`proposal/${id}/verifikasi`, data),
  },

  // LPJ
  lpj: {
    list: () => 
      window.axios.get('lpj'),
    
    get: (id) => 
      window.axios.get(`lpj/${id}`),
    
    create: (data) => 
      window.axios.post('lpj', data),
    
    submitRevision: (id, data) => 
      window.axios.post(`lpj/${id}/revisi`, data),
    
    verify: (id, data) => 
      window.axios.patch(`lpj/${id}/verifikasi`, data),
  },

  // Prestasi
  prestasi: {
    list: () => 
      window.axios.get('prestasi'),
    
    get: (id) => 
      window.axios.get(`prestasi/${id}`),
    
    create: (data) => 
      window.axios.post('prestasi', data),
    
    update: (id, data) => 
      window.axios.put(`prestasi/${id}`, data),
    
    delete: (id) => 
      window.axios.delete(`prestasi/${id}`),
    
    checkStatus: (id) => 
      window.axios.get(`prestasi/${id}/status`),
    
    verify: (id, data) => 
      window.axios.patch(`prestasi/${id}/verifikasi`, data),
    
    addAnggota: (id, data) => 
      window.axios.post(`prestasi/${id}/anggota`, data),
    
    deleteAnggota: (id, anggotaId) => 
      window.axios.delete(`prestasi/${id}/anggota/${anggotaId}`),
    
    addDosen: (id, data) => 
      window.axios.post(`prestasi/${id}/dosen`, data),
    
    deleteDosen: (id, dosenId) => 
      window.axios.delete(`prestasi/${id}/dosen/${dosenId}`),
  },

  // Template
  template: {
    list: () => 
      window.axios.get('template'),
    
    get: (id) => 
      window.axios.get(`template/${id}`),
    
    create: (data) => 
      window.axios.post('template', data),
    
    update: (id, data) => 
      window.axios.put(`template/${id}`, data),
    
    delete: (id) => 
      window.axios.delete(`template/${id}`),
  },

  // Informasi
  informasi: {
    list: () => 
      window.axios.get('informasi'),
    
    get: (id) => 
      window.axios.get(`informasi/${id}`),
  },

  // User
  user: {
    list: () => 
      window.axios.get('user'),
    
    get: (id) => 
      window.axios.get(`user/${id}`),
    
    update: (id, data) => 
      window.axios.put(`user/${id}`, data),
    
    delete: (id) => 
      window.axios.delete(`user/${id}`),
  },

  // Monitoring
  monitoring: {
    dashboard: () => 
      window.axios.get('monitoring/dashboard'),
  },
};

export default api;
