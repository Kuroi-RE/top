<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export PDF — Prestasi Mahasiswa</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .title {
            font-size: 22pt;
            font-weight: bold;
            margin: 0;
            color: #0f172a;
        }
        .subtitle {
            font-size: 10pt;
            color: #64748b;
            margin-top: 5px;
        }
        .summary-wrapper {
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-left: -10px;
            margin-right: -10px;
        }
        .summary-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 15px;
            width: 33.33%;
            vertical-align: top;
        }
        .sc-label {
            font-size: 8pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .sc-value {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0;
        }
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .main-table th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        .main-table td {
            padding: 10px;
            font-size: 8pt;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .pill {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 7pt;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }
        .pill-amber { background-color: #fef3c7; color: #b45309; }
        .pill-sky { background-color: #e0f2fe; color: #0369a1; }
        .text-center { text-align: center; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header-table">
        <h1 class="title">Export Prestasi Mahasiswa</h1>
        <p class="subtitle">Rekap data prestasi mahasiswa aktif &mdash; {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <div class="section-title">Filter Pencarian</div>
    <table class="summary-table">
        <tr>
            <td class="summary-card">
                <div class="sc-label">Prestasi</div>
                <div class="sc-value">Semua Prestasi</div>
            </td>
            <td class="summary-card">
                <div class="sc-label">Tingkat</div>
                <div class="sc-value">Semua Tingkat</div>
            </td>
            <td class="summary-card">
                <div class="sc-label">Pencarian</div>
                <div class="sc-value">-</div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px;">
        <div class="section-title">Daftar Prestasi</div>
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 70px;">NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Prestasi</th>
                    <th>Nama Event</th>
                    <th>Penyelenggara</th>
                    <th>Tingkat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prestasi as $item)
                    <tr>
                        <td>{{ $item['nim'] }}</td>
                        <td style="font-weight: bold;">{{ $item['nama'] }}</td>
                        <td>{{ $item['prodi'] }}</td>
                        <td>
                            <span class="pill pill-amber">{{ $item['prestasi'] }}</span>
                        </td>
                        <td>{{ $item['nama_event'] }}</td>
                        <td>{{ $item['penyelenggara'] }}</td>
                        <td>
                            <span class="pill pill-sky">{{ $item['tingkat'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 30px; color: #94a3b8;">Belum ada data prestasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        TOP &copy; {{ date('Y') }} &mdash; Kemahasiswaan Telkom University Purwokerto
    </div>
</body>
</html>