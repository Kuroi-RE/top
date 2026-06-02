@extends('layouts.app')

@section('title', 'Template Dokumen')

@section('content')
<style>
    .template-shell {
        max-width: 1200px;
        margin: 0 auto;
        animation: fade-in 0.6s ease-out both;
    }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .premium-card {
        background: linear-gradient(135deg, #ffffff 0%, #fbfdff 100%);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid rgba(2,6,23,0.05);
        overflow: hidden;
    }

    .form-input-premium {
        background: #f8fafc;
        border: 1px solid rgba(2,6,23,0.08);
        border-radius: 14px;
        padding: 10px 16px;
        transition: all 0.2s ease;
        outline: none;
        width: 100%;
        box-sizing: border-box;
    }

    .form-input-premium:focus {
        background: #ffffff;
        border-color: #c1121f;
        box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.08);
    }

    .input-container {
        position: relative;
        width: 100%;
    }

    .input-container .icon-left {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-container input.has-icon-left {
        padding-left: 40px !important;
    }

    .btn-add-premium {
        background: linear-gradient(135deg, #c1121f 0%, #780116 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 16px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(193, 18, 31, 0.2);
    }

    .btn-add-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(193, 18, 31, 0.3);
    }

    .premium-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .premium-table th {
        background: #f8fafc;
        padding: 16px 24px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid rgba(2,6,23,0.05);
    }

    .premium-table td {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(2,6,23,0.03);
        color: #1e293b;
        vertical-align: middle;
    }

    .doc-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #f1f5f9;
        border-radius: 12px;
        color: #475569;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .doc-badge:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .action-btn-circle {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-edit { color: #0f172a; background: #f1f5f9; }
    .btn-edit:hover { background: #e2e8f0; border-color: #cbd5e1; }
    
    .btn-delete { color: #c1121f; background: #fff1f2; }
    .btn-delete:hover { background: #fee2e2; border-color: #fecaca; }

    .state-card {
        border: 1px dashed rgba(2, 6, 23, 0.12);
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        color: #64748b;
        border-radius: 18px;
        padding: 28px;
        text-align: center;
    }
</style>

<div class="template-shell">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Template Dokumen</h1>
            <p class="text-slate-500 mt-1">Kelola berkas panduan dan formulir resmi untuk Ormawa</p>
        </div>
        <a href="{{ route('admin.input_template_dokumen') }}" class="btn-add-premium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Template
        </a>
    </div>

    <!-- Toolbar -->
    <div class="premium-card p-4 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-slate-500">Show</span>
                <select id="template-page-size" class="form-input-premium pr-8">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span class="text-sm font-bold text-slate-500">entries</span>
            </div>
            
            <div class="input-container w-full md:w-80">
                <input id="template-search" type="text" placeholder="Cari nama dokumen..." class="form-input-premium has-icon-left">
                <div class="icon-left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="premium-card">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th class="w-1/2">Nama Dokumen</th>
                        <th class="text-center">Tipe Berkas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="template-table-body">
                    <tr>
                        <td colspan="3">
                            <div class="state-card">
                                Memuat template dokumen...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50/50 border-t border-slate-100">
            <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                <span id="template-pagination-info">Showing 0 entries</span>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 disabled:opacity-50" disabled>Previous</button>
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 disabled:opacity-50" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('template-table-body');
        const searchInput = document.getElementById('template-search');
        const pageSizeSelect = document.getElementById('template-page-size');
        const paginationInfo = document.getElementById('template-pagination-info');

        let templates = [];

        function formatDate(value) {
            if (!value) return '-';
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) return '-';
            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            }).format(date);
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderEmpty(message) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3">
                        <div class="state-card">${message}</div>
                    </td>
                </tr>
            `;
            paginationInfo.textContent = 'Showing 0 entries';
        }

        function renderRows(items) {
            if (!items.length) {
                renderEmpty('Tidak ada template dokumen yang cocok.');
                return;
            }

            tableBody.innerHTML = items.map(function (template) {
                const downloadUrl = `/api/v1/template/${template.id_template}/download`;
                const name = escapeHtml(template.nama_template || '-');
                const type = escapeHtml(template.jenis_template || '-');

                return `
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-slate-900 font-bold text-base">${name}</span>
                                <span class="text-slate-500 text-xs font-normal mt-1">Terakhir diperbarui: ${formatDate(template.updated_at)}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="doc-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                ${type}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="${downloadUrl}" class="action-btn-circle btn-edit" title="Download" aria-label="Download ${name}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-8 4h8" />
                                    </svg>
                                </a>
                                <button type="button" class="action-btn-circle btn-delete" data-template-id="${template.id_template}" title="Hapus" aria-label="Hapus ${name}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            const firstIndex = 1;
            const lastIndex = items.length;
            paginationInfo.textContent = `Showing ${firstIndex} to ${lastIndex} of ${templates.length} entries`;
        }

        function applyFilters() {
            const query = (searchInput?.value || '').trim().toLowerCase();
            const pageSize = Number(pageSizeSelect?.value || 10);
            const filtered = templates.filter(function (template) {
                return (template.nama_template || '').toLowerCase().includes(query)
                    || (template.jenis_template || '').toLowerCase().includes(query);
            });

            renderRows(filtered.slice(0, pageSize));
        }

        async function deleteTemplate(templateId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const token = localStorage.getItem('topkema_api_token');
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }

            const response = await fetch(`/api/v1/template/${templateId}`, {
                method: 'DELETE',
                headers: headers,
                credentials: 'same-origin',
            });

            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(payload.message || 'Gagal menghapus template dokumen.');
            }

            templates = templates.filter(function (template) {
                return String(template.id_template) !== String(templateId);
            });

            applyFilters();
        }

        async function loadTemplates() {
            try {
                const token = localStorage.getItem('topkema_api_token');
                const headers = { 'Accept': 'application/json' };
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch('/api/v1/template?per_page=100', {
                    headers: headers,
                    credentials: 'same-origin',
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(payload.message || 'Gagal memuat template dokumen.');
                }

                templates = Array.isArray(payload.data) ? payload.data : (payload.data?.data || []);
                applyFilters();
            } catch (error) {
                renderEmpty(error.message || 'Gagal memuat template dokumen.');
            }
        }

        searchInput?.addEventListener('input', applyFilters);
        pageSizeSelect?.addEventListener('change', applyFilters);

        tableBody.addEventListener('click', async function (event) {
            const button = event.target.closest('button[data-template-id]');
            if (!button) return;

            const templateId = button.dataset.templateId;
            if (!templateId) return;

            if (!window.confirm('Hapus template dokumen ini?')) return;

            button.disabled = true;

            try {
                await deleteTemplate(templateId);
            } catch (error) {
                alert(error.message || 'Gagal menghapus template dokumen.');
            } finally {
                button.disabled = false;
            }
        });

        loadTemplates();
    });
</script>
@endsection
