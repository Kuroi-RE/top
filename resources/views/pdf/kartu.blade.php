<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Verifikasi Prestasi</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            background-color: #f3efe7;
            padding: 20px;
        }
        .card {
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #eadfce;
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            padding: 20px;
            background: #7f1d1d;
            color: #ffffff;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .card-subtitle {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        .card-body {
            padding: 20px;
        }
        .layout-table {
            width: 100%;
            border-collapse: collapse;
        }
        .layout-td {
            vertical-align: top;
            padding: 10px;
        }
        .panel {
            border: 1px solid #eadfce;
            border-radius: 12px;
            background-color: #fafafa;
            padding: 15px;
            margin-bottom: 15px;
        }
        .panel-title {
            font-size: 12px;
            font-weight: bold;
            color: #7f1d1d;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 1px solid #eadfce;
            padding-bottom: 5px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 6px 0;
            border-bottom: 1px dashed rgba(127, 29, 29, 0.1);
        }
        .meta-table tr:last-child td {
            border-bottom: none;
        }
        .meta-label {
            color: #666;
            width: 120px;
            font-size: 11px;
        }
        .meta-separator {
            width: 10px;
            color: #b45309;
            font-weight: bold;
        }
        .meta-value {
            font-weight: bold;
            color: #333;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        .stat-box {
            background: #fff8ef;
            border: 1px solid rgba(127, 29, 29, 0.08);
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }
        .stat-name {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }
        .stat-count {
            font-size: 24px;
            font-weight: bold;
            color: #7f1d1d;
        }
        .statement-box {
            background-color: #f8d7da;
            color: #7f1d1d;
            border-radius: 10px;
            padding: 12px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 15px;
            line-height: 1.5;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .signature-box {
            border: 1px solid #eadfce;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 12px;
            text-align: center;
            height: 120px;
        }
        .signature-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 10px;
            text-align: left;
        }
        .signature-note {
            font-size: 9px;
            color: #666;
            text-align: left;
            line-height: 1.3;
        }
        .signature-name {
            font-weight: bold;
            color: #7f1d1d;
            margin-top: 30px;
            border-top: 1px solid #eadfce;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-header">
            <div class="badge">Kartu Verifikasi Prestasi</div>
            <div class="card-title">{{ $namaLengkap }}</div>
            <p class="card-subtitle">
                Kartu ini berisi identitas mahasiswa, ringkasan prestasi, dan jumlah dokumen pendukung sebagai hasil pemindaian QR code.
            </p>
        </div>

        <div class="card-body">
            <table class="layout-table">
                <tr>
                    <td class="layout-td" style="width: 55%;">
                        <div class="panel">
                            <div class="panel-title">Data Mahasiswa</div>
                            <table class="meta-table">
                                <tr>
                                    <td class="meta-label">Nama</td>
                                    <td class="meta-separator">:</td>
                                    <td class="meta-value">{{ $namaLengkap }}</td>
                                </tr>
                                <tr>
                                    <td class="meta-label">NIM</td>
                                    <td class="meta-separator">:</td>
                                    <td class="meta-value">{{ $user->nim ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="meta-label">Program Studi</td>
                                    <td class="meta-separator">:</td>
                                    <td class="meta-value">{{ $user->prodi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="meta-label">Tanggal Lulus</td>
                                    <td class="meta-separator">:</td>
                                    <td class="meta-value">{{ $tanggalLulus }}</td>
                                </tr>
                                <tr>
                                    <td class="meta-label">Jenjang Pendidikan</td>
                                    <td class="meta-separator">:</td>
                                    <td class="meta-value">{{ $jenjangPendidikan }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td class="layout-td" style="width: 45%;">
                        <div class="panel" style="height: 100%;">
                            <div class="panel-title">Ringkasan</div>
                            <table class="stats-table">
                                <tr>
                                    <td style="width: 50%; padding-right: 5px;">
                                        <div class="stat-box">
                                            <span class="stat-name">Total Prestasi</span>
                                            <div class="stat-count">{{ $totalPrestasi }}</div>
                                        </div>
                                    </td>
                                    <td style="width: 50%; padding-left: 5px;">
                                        <div class="stat-box">
                                            <span class="stat-name">Total Dokumen</span>
                                            <div class="stat-count">{{ $totalDokumen }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div class="statement-box">
                                Dokumen ini ditandatangani oleh Kadarisman, S.Si dan dapat digunakan sebagai halaman verifikasi data prestasi mahasiswa.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="layout-table" style="margin-top: 10px;">
                <tr>
                    <td style="width: 33.33%; padding: 5px;">
                        <div class="signature-box">
                            <div class="signature-label">Verifikasi data</div>
                            <div class="signature-note">
                                Informasi berasal dari data prestasi dan dokumen pendukung mahasiswa.
                            </div>
                            <div class="signature-name">Kadarisman, S.Si</div>
                        </div>
                    </td>
                    <td style="width: 33.33%; padding: 5px;">
                        <div class="signature-box">
                            <div class="signature-label">Status Dokumen</div>
                            <div class="signature-note">
                                Terhubung ke QR code pada transkrip prestasi.
                            </div>
                            <div class="signature-name">Terverifikasi</div>
                        </div>
                    </td>
                    <td style="width: 33.33%; padding: 5px;">
                        <div class="signature-box">
                            <div class="signature-label">Tanggal Cetak</div>
                            <div class="signature-note">
                                {{ now()->format('d-m-Y') }}
                            </div>
                            <div class="signature-name">Universitas Telkom</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="footer">
                Kartu ini dibuat untuk kebutuhan informasi dan verifikasi data prestasi mahasiswa.
            </div>
        </div>
    </div>

</body>
</html>
