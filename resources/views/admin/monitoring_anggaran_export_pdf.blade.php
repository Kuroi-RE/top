<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export PDF — Monitoring Anggaran</title>
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
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0;
        }
        .sc-caption {
            font-size: 8pt;
            color: #94a3b8;
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
            font-size: 9pt;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
        }
        .pill-done    { background-color: #dcfce7; color: #15803d; }
        .pill-pending { background-color: #fef3c7; color: #b45309; }
        .pill-info    { background-color: #e0f2fe; color: #0369a1; }
        .pill-revisi  { background-color: #ffe4e6; color: #be123c; }
        .pill-default { background-color: #f1f5f9; color: #475569; }
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
        <h1 class="title">Monitoring Anggaran</h1>
        <p class="subtitle">Ringkasan transparansi anggaran proposal &mdash; {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <div class="section-title">Ringkasan Proposal</div>
    <table class="summary-table">
        <tr>
            <td class="summary-card">
                <div class="sc-label">Total Proposal</div>
                <div class="sc-value">{{ $summary['totalProposal'] }}</div>
                <div class="sc-caption">Data proposal yang tercatat</div>
            </td>
            <td class="summary-card">
                <div class="sc-label">Total Anggaran Diajukan</div>
                <div class="sc-value">Rp {{ number_format($summary['totalDiajukan'], 0, ',', '.') }}</div>
                <div class="sc-caption">Total pengajuan anggaran</div>
            </td>
            <td class="summary-card">
                <div class="sc-label">Total Anggaran Disetujui</div>
                <div class="sc-value">Rp {{ number_format($summary['totalDisetujui'], 0, ',', '.') }}</div>
                <div class="sc-caption">Anggaran yang sudah disetujui</div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px;">
        <div class="section-title">Daftar Proposal Terbaru</div>
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 40px;">TW</th>
                    <th>Nama Kegiatan</th>
                    <th style="width: 100px;">Besar Ajuan</th>
                    <th style="width: 100px;">Disetujui</th>
                    <th style="width: 80px;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($proposals as $proposal)
                    @php
                        $pillClass = match(strtolower($proposal->status ?? '')) {
                            'disetujui'  => 'pill-done',
                            'pencairan'  => 'pill-info',
                            'revisi'     => 'pill-revisi',
                            'menunggu'   => 'pill-pending',
                            default      => 'pill-default',
                        };
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $proposal->ajuan_triwulan }}</td>
                        <td>{{ $proposal->nama_kegiatan }}</td>
                        <td>Rp {{ number_format($proposal->besar_ajuan ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($proposal->anggaran_disetujui ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="pill {{ $pillClass }}">{{ $proposal->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 30px; color: #94a3b8;">Belum ada data proposal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        TOP &copy; {{ date('Y') }} &mdash; TOPKEMA Telkom University Purwokerto
    </div>
</body>
</html>
