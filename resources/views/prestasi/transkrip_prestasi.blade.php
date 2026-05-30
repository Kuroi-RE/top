@extends('layouts.app')

@section('title', 'Transkrip Prestasi Mahasiswa')

@section('content')

@push('styles')
<style>
    .transkrip-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        font-family: 'Arial', sans-serif;
    }

    .header {
        position: relative;
        text-align: center;
        margin-bottom: 10px;
        padding-top: 60px;
    }

    .logo-section {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 20px;
        position: absolute;
        top: 0;
        left: 0;
    }

    .logo-section img {
        height: 50px;
        width: auto;
    }

    .header h1 {
        font-size: 48px;
        font-weight: bold;
        margin: 5px 0;
        color: #333;
    }

    .header p {
        font-size: 12px;
        color: #666;
        margin: 2px 0;
    }

    .document-title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 5px 0;
        text-transform: uppercase;
        color: #c1121f;
    }

    .document-subtitle {
        text-align: center;
        font-size: 12px;
        font-style: italic;
        margin-bottom: 5px;
        color: #666;
    }

    .student-info {
        margin-bottom: 20px;
        font-size: 13px;
        line-height: 1.8;
    }

    .info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 8px;
    }

    .info-item {
        display: flex;
    }

    .info-label {
        font-weight: bold;
        width: 180px;
    }

    .info-separator {
        margin: 0 5px;
    }

    .info-value {
        flex: 1;
    }

    .transkrip-table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 13px;
    }

    .transkrip-table thead {
        background-color: #c1121f;
        color: white;
    }

    .transkrip-table th,
    .transkrip-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .transkrip-table th {
        font-weight: bold;
        text-transform: uppercase;
    }

    .transkrip-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .transkrip-table tbody tr:hover {
        background-color: #f0f0f0;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }

    .badge-internasional {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-nasional {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-regional {
        background-color: #dcfce7;
        color: #15803d;
    }

    .signature-section {
        margin-top: 50px;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 40px;
        text-align: center;
        font-size: 12px;
    }

    .signature-block {
        border-top: 1px solid #333;
        padding-top: 60px;
    }

    .signature-label {
        font-weight: bold;
        margin-top: 10px;
    }

    .qr-code {
        text-align: center;
        margin: 20px 0;
    }

    .qr-code img {
        width: 120px;
        height: 120px;
    }

    .footer-note {
        text-align: center;
        font-size: 11px;
        color: #666;
        margin-top: 20px;
    }

    .export-button {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary {
        background-color: #c1121f;
        color: white;
    }

    .btn-primary:hover {
        background-color: #a00d1a;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    @media print {
        /* Sembunyikan elemen UI yang tidak perlu dicetak */
        #sidebar, 
        header, 
        .print\:hidden, 
        #sidebar-toggle-btn,
        .z-40,
        .theme-toggle,
        #user-wrapper,
        #download-btn-container {
            display: none !important;
        }
        
        /* Lepas semua batasan layout (flex, overflow, height) agar halaman bisa full width & paginasi jalan */
        html, body, .h-screen, .overflow-hidden, .flex, .flex-1, #main-content, main, .p-4, .sm\:p-6 {
            height: auto !important;
            min-height: auto !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow: visible !important;
            display: block !important;
            padding: 0 !important;
            margin: 0 !important;
            background: none !important;
        }

        /* Pastikan container transkrip menempati ukuran penuh kertas */
        .transkrip-container {
            position: relative !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
        }
        
        /* Perbaikan tabel jika terpotong antar halaman */
        table {
            page-break-inside: auto;
            width: 100% !important;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        /* Ukuran kertas standar */
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    .empty-state p {
        font-size: 14px;
    }
</style>
@endpush

<div class="p-4 sm:p-6">
    
    <!-- Top Right Action Bar -->
    <div id="download-btn-container" class="max-w-[900px] mx-auto mb-2 flex justify-end print:hidden">
        <button onclick="window.print()" class="relative text-gray-400 hover:text-gray-600 p-2 transition-all group" title="Download PDF">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span class="absolute right-full mr-2 top-1/2 -translate-y-1/2 bg-gray-700 text-white text-[11px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Download PDF</span>
        </button>
    </div>


    @if($prestasis->isEmpty())
        <div class="empty-state">
            <p>Belum ada prestasi untuk ditampilkan. Silakan tambahkan prestasi terlebih dahulu.</p>
        </div>
    @else
        <div class="transkrip-container">
            <div class="header">
                <div class="logo-section">
                    <img src="{{ asset('logo_TUP.png') }}" alt="Logo TUP" style="height: 45px; width: auto; object-fit: contain;">
                    <img src="{{ asset('logo_direktorat.png') }}" alt="Direktorat Kemahasiswaan, Karier dan Alumni" style="height: 35px; width: auto; object-fit: contain;">
                </div>
                <h1>Universitas Telkom</h1>
            </div>

            <div class="document-title">Transkrip Prestasi Mahasiswa</div>
            <div class="document-subtitle">STUDENT PRESTATIONS TRANSCRIPT</div>
</br>
            <div class="student-info">
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Nama/Name</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $user->nama_depan }} {{ $user->nama_belakang ?? '' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nomor Induk Mahasiswa/Student's ID</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $user->nim ?? '-' }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Tempat & Tanggal Lahir/Place & Date of Birth</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Program Studi/Program</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $user->prodi ?? '-' }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Tanggal Cetak/Date of Print</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ now()->format('d-m-Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jenjang Pendidikan/Level of Education</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">S1 (Strata 1)</span>
                    </div>
                </div>
            </div>

            <table class="transkrip-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">No</th>
                        <th style="width: 12%;">Tahun</th>
                        <th style="width: 35%;">Nama Kegiatan</th>
                        <th style="width: 20%;">Tingkat</th>
                        <th style="width: 25%;">Prestasi dicapai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasis as $index => $prestasi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $prestasi->created_at->format('Y') }}</td>
                            <td>{{ $prestasi->nama_kompetisi }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($prestasi->tingkat) }}">
                                    {{ $prestasi->tingkat }}
                                </span>
                            </td>
                            <td>{{ $prestasi->capaian ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999;">
                                Tidak ada data prestasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="footer-note">
                <p>Pencapaian prestasi saat ini: {{ now()->locale('id')->format('j F Y') }}</p>
            </div>

            <div class="signature-section">
                <div class="signature-block">
                    <p style="margin-bottom: 5px;"><strong>Kepala Urusan</strong></p>
                    <p style="margin-bottom: 40px; font-size: 11px;">Kemahasiswaan, Karier dan Alumni</p>
                    <p class="signature-label">Kadarisman, S.Si</p>
                </div>

                <div class="qr-code">
                    @if(!empty($qrCodeUrl))
                        <img src="{{ $qrCodeUrl }}" alt="QR Code kartu verifikasi prestasi {{ $nim }}" style="margin: 0 auto; display: block; width: 100px; height: 100px;">
                    @else
                        <div class="placeholder-box flex-1 w-24 h-24" style="margin: 0 auto;">
                            <div class="text-center">
                                <p class="text-xs">QR Code</p>
                                <p class="text-[10px] text-gray-500">Link kartu belum tersedia</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="signature-block">
                    <p style="margin-bottom: 5px;"><strong>Mahasiswa</strong></p>
                    <p style="margin-bottom: 40px; font-size: 11px;">Student</p>
                    <p class="signature-label">{{ $user->nama_depan }} {{ $user->nama_belakang ?? '' }}</p>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
