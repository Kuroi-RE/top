<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF Proposal Kegiatan</title>
    <style>
        :root {
            color-scheme: light;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #0f172a;
            background: #f8fafc;
        }

        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 24px;
        }

        .title {
            margin: 0;
            font-size: 26px;
            line-height: 1.2;
        }

        .subtitle {
            margin: 8px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 9999px;
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 700;
            font-size: 13px;
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        thead {
            background: #f1f5f9;
        }

        th,
        td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 13px;
        }

        th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #334155;
        }

        .status {
            display: inline-flex;
            min-width: 96px;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 12px;
        }

        .done { background: #dcfce7; color: #15803d; }
        .waiting { background: #e0f2fe; color: #0369a1; }
        .revisi { background: #ffe4e6; color: #be123c; }
        .rejected { background: #fee2e2; color: #b91c1c; }
        .new { background: #e2e8f0; color: #334155; }

        .footer {
            margin-top: 18px;
            color: #64748b;
            font-size: 12px;
            text-align: right;
        }

        @media print {
            body {
                background: white;
            }

            .page {
                padding: 0;
            }

            .footer {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                display: grid;
                grid-template-columns: 1fr;
                align-items: start;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    @php
        $formatCurrency = function ($value) {
            if ($value === null || $value === '') {
                return '-';
            }

            if (is_numeric($value)) {
                return 'Rp ' . number_format((float) $value, 0, ',', '.');
            }

            return (string) $value;
        };

        $formatDate = function ($value) {
            if (!$value) {
                return '-';
            }

            try {
                return \Carbon\Carbon::parse($value)->format('d/m/Y');
            } catch (\Throwable $e) {
                return (string) $value;
            }
        };
    @endphp

    <div class="page">
        <div class="header">
            <div>
                <h1 class="title">Export Proposal Kegiatan</h1>
                <p class="subtitle">Daftar seluruh proposal ormawa</p>
            </div>
            <div class="chip">Siap disimpan sebagai PDF</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TW</th>
                    <th>Nama Kegiatan</th>
                    <th>Pelaksanaan</th>
                    <th>Ajuan Dana</th>
                    <th>Anggaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($proposals as $proposal)
                    <tr>
                        <td>{{ $proposal->id_proposal }}</td>
                        <td>{{ $proposal->ajuan_triwulan }}</td>
                        <td>{{ $proposal->nama_kegiatan }}</td>
                        <td>{{ $formatDate($proposal->waktu_kegiatan) }}</td>
                        <td>{{ $formatCurrency($proposal->besar_ajuan) }}</td>
                        <td>{{ $formatCurrency($proposal->anggaran_disetujui) }}</td>
                        <td>
                            <span class="status {{ $statusStyles[$proposal->status] ?? 'new' }}">{{ $proposal->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Belum ada data proposal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">TOP © 2026 Kemahasiswaan Telkom University Purwokerto</div>
    </div>

    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</body>
</html>
