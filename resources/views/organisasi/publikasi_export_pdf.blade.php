<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF Publikasi Kegiatan</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }

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

        thead { background: #f1f5f9; }

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

        .file-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 9999px;
            background: #e2e8f0;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
        }

        .footer {
            margin-top: 18px;
            color: #64748b;
            font-size: 12px;
            text-align: right;
        }

        @media print {
            body { background: white; }
            .page { padding: 0; }
            .footer { display: none; }
        }

        @media (max-width: 768px) {
            .header { display: grid; grid-template-columns: 1fr; align-items: start; }
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
    @php
        $publikasiItems = $publikasiItems ?? collect([]);
    @endphp

    <div class="page">
        <div class="header">
            <div>
                <h1 class="title">Export Publikasi Kegiatan</h1>
                <p class="subtitle">Daftar publikasi kegiatan ormawa</p>
            </div>
            <div class="chip">Siap disimpan sebagai PDF</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Caption</th>
                    <th>Link</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publikasiItems as $item)
                    <tr>
                        <td>{{ $item['judul'] ?? '-' }}</td>
                        <td>{{ $item['caption'] ?? '-' }}</td>
                        <td>{{ $item['link'] ?? '-' }}</td>
                        <td>
                            @if (!empty($item['file']))
                                <span class="file-chip">Ada</span>
                            @else
                                <span class="file-chip">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#64748b;">Belum ada publikasi.</td>
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
