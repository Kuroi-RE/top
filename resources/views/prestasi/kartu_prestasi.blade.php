<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Verifikasi Prestasi</title>
    <style>
        :root {
            --bg: #f3efe7;
            --card: #fffaf2;
            --ink: #1f2937;
            --muted: #6b7280;
            --accent: #7f1d1d;
            --accent-soft: #f8d7da;
            --line: #eadfce;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at top left, rgba(127, 29, 29, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(180, 83, 9, 0.12), transparent 24%),
                var(--bg);
            color: var(--ink);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            width: min(920px, 100%);
            background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(255,250,242,0.98));
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: 0 24px 60px rgba(31, 41, 55, 0.12);
            overflow: hidden;
        }

        .card-top {
            padding: 30px 32px 20px;
            background: linear-gradient(135deg, rgba(127, 29, 29, 0.96), rgba(180, 83, 9, 0.92));
            color: #fff;
            position: relative;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .title {
            margin: 16px 0 8px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.1;
        }

        .subtitle {
            margin: 0;
            max-width: 700px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 14px;
            line-height: 1.7;
        }

        .card-body {
            padding: 28px 32px 32px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 24px;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.8);
            padding: 22px;
        }

        .panel h2 {
            margin: 0 0 18px;
            font-size: 16px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--accent);
        }

        .meta-list {
            display: grid;
            gap: 14px;
        }

        .meta-item {
            display: grid;
            grid-template-columns: 170px 16px 1fr;
            gap: 8px;
            align-items: start;
            padding-bottom: 14px;
            border-bottom: 1px dashed rgba(127, 29, 29, 0.14);
        }

        .meta-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .label {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .separator {
            color: #b45309;
            font-weight: 700;
        }

        .value {
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.5;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .stat {
            border-radius: 18px;
            padding: 18px;
            background: linear-gradient(180deg, #fff, #fff8ef);
            border: 1px solid rgba(127, 29, 29, 0.08);
        }

        .stat .name {
            display: block;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 8px;
        }

        .stat .count {
            font-size: 32px;
            font-weight: 800;
            color: var(--accent);
            line-height: 1;
        }

        .statement {
            margin-top: 14px;
            padding: 16px 18px;
            border-radius: 18px;
            background: var(--accent-soft);
            color: #7f1d1d;
            font-size: 13px;
            line-height: 1.7;
            font-weight: 600;
        }

        .signature-row {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .signature-block {
            min-height: 152px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: #fff;
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .signature-label {
            font-size: 12px;
            color: var(--muted);
        }

        .signature-name {
            margin-top: 32px;
            padding-top: 14px;
            border-top: 1px solid rgba(31, 41, 55, 0.12);
            font-weight: 800;
            color: var(--accent);
            text-align: center;
        }

        .signature-note {
            text-align: center;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .empty {
            border: 1px dashed rgba(127, 29, 29, 0.2);
            border-radius: 20px;
            padding: 20px;
            color: var(--muted);
            background: rgba(255, 255, 255, 0.68);
        }

        @media (max-width: 800px) {
            .card-top,
            .card-body {
                padding-left: 20px;
                padding-right: 20px;
            }

            .grid,
            .signature-row,
            .stats {
                grid-template-columns: 1fr;
            }

            .meta-item {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .separator {
                display: none;
            }
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .card {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <main class="card">
        <section class="card-top">
            <div class="badge">Kartu Verifikasi Prestasi</div>
            <h1 class="title">{{ $namaLengkap }}</h1>
            <p class="subtitle">
                Kartu ini berisi identitas mahasiswa, ringkasan prestasi, dan jumlah dokumen pendukung
                sebagai hasil pemindaian QR code.
            </p>
        </section>

        <section class="card-body">
            <div class="grid">
                <article class="panel">
                    <h2>Data Mahasiswa</h2>
                    <div class="meta-list">
                        <div class="meta-item">
                            <span class="label">Nama</span>
                            <span class="separator">:</span>
                            <span class="value">{{ $namaLengkap }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="label">NIM</span>
                            <span class="separator">:</span>
                            <span class="value">{{ $user->nim ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="label">Program Studi</span>
                            <span class="separator">:</span>
                            <span class="value">{{ $user->prodi ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="label">Tanggal Lulus</span>
                            <span class="separator">:</span>
                            <span class="value">{{ $tanggalLulus }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="label">Jenjang Pendidikan</span>
                            <span class="separator">:</span>
                            <span class="value">{{ $jenjangPendidikan }}</span>
                        </div>
                    </div>
                </article>

                <aside class="panel">
                    <h2>Ringkasan</h2>
                    <div class="stats">
                        <div class="stat">
                            <span class="name">Total Prestasi</span>
                            <div class="count">{{ $totalPrestasi }}</div>
                        </div>
                        <div class="stat">
                            <span class="name">Total Dokumen</span>
                            <div class="count">{{ $totalDokumen }}</div>
                        </div>
                    </div>

                    <div class="statement">
                        Dokumen ini ditandatangani oleh Kadarisman, S.Si dan dapat digunakan sebagai halaman
                        verifikasi data prestasi mahasiswa.
                    </div>
                </aside>
            </div>

            <div class="signature-row">
                <div class="signature-block">
                    <div>
                        <div class="signature-label">Disusun untuk verifikasi data prestasi mahasiswa</div>
                        <div class="signature-note" style="margin-top: 10px; text-align: left;">
                            Informasi yang ditampilkan berasal dari data prestasi dan dokumen pendukung yang
                            terhubung ke akun mahasiswa.
                        </div>
                    </div>
                    <div class="signature-name">Kadarisman, S.Si</div>
                </div>

                <div class="signature-block">
                    <div>
                        <div class="signature-label">Status dokumen</div>
                        <div class="signature-note" style="margin-top: 10px; text-align: left;">
                            Terhubung ke QR code pada transkrip prestasi dan mengarah ke kartu verifikasi ini.
                        </div>
                    </div>
                    <div class="signature-name">Terverifikasi</div>
                </div>

                <div class="signature-block">
                    <div>
                        <div class="signature-label">Tanggal cetak kartu</div>
                        <div class="signature-note" style="margin-top: 10px; text-align: left;">
                            {{ now()->format('d-m-Y') }}
                        </div>
                    </div>
                    <div class="signature-name">Universitas Telkom</div>
                </div>
            </div>

            @if($prestasis->isEmpty())
                <div class="empty" style="margin-top: 18px;">
                    Belum ada data prestasi yang tercatat untuk mahasiswa ini.
                </div>
            @endif

            <div class="footer">
                Kartu ini dibuat untuk kebutuhan informasi dan verifikasi data prestasi mahasiswa.
            </div>
        </section>
    </main>
</body>
</html>
