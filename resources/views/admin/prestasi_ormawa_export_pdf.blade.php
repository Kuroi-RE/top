<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF Prestasi Ormawa</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; color: #0f172a; background: #f8fafc; }
        .page { max-width: 1240px; margin: 0 auto; padding: 32px 24px 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 24px; }
        h1 { margin: 0; font-size: 26px; }
        p { margin: 8px 0 0; color: #64748b; }
        .chip { padding: 10px 14px; border-radius: 9999px; background: #fee2e2; color: #b91c1c; font-weight: 700; font-size: 13px; white-space: nowrap; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08); }
        thead { background: #f1f5f9; }
        th, td { padding: 14px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: top; font-size: 13px; }
        th { font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: #334155; }
        .status { display: inline-flex; min-width: 96px; justify-content: center; padding: 6px 12px; border-radius: 9999px; font-weight: 700; font-size: 12px; }
        .done { background: #dcfce7; color: #15803d; }
        .pending { background: #fef3c7; color: #b45309; }
        .info { background: #e0f2fe; color: #0369a1; }
        .revisi { background: #ffe4e6; color: #be123c; }
        .new { background: #e2e8f0; color: #334155; }
        .footer { margin-top: 18px; color: #64748b; font-size: 12px; text-align: right; }
        @media print { body { background: #fff; } .page { padding: 0; } .footer { display: none; } }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <h1>Export Prestasi Ormawa</h1>
                <p>Rekap data prestasi organisasi mahasiswa</p>
            </div>
            <div class="chip">Siap disimpan sebagai PDF</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>TW</th>
                    <th>Ormawa</th>
                    <th>Nama Kegiatan</th>
                    <th>Resiko</th>
                    <th>Waktu Kegiatan</th>
                    <th>Besar Ajuan</th>
                    <th>Besar Anggaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $activity['tw'] }}</td>
                        <td>{{ $activity['ormawa'] }}</td>
                        <td>{{ $activity['nama_kegiatan'] }}</td>
                        <td>{{ $activity['resiko'] }}</td>
                        <td>{{ $activity['waktu'] }}</td>
                        <td>{{ $activity['ajuan'] }}</td>
                        <td>{{ $activity['anggaran'] }}</td>
                        <td><span class="status {{ $statusStyles[$activity['status']] ?? 'new' }}">{{ $activity['status'] }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">TOP © 2026 Kemahasiswaan Telkom University Purwokerto</div>
    </div>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>