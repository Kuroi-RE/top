<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - TOP KEMA Telkom</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #c0392b;
            color: #ffffff;
            padding: 24px 32px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .body {
            padding: 32px;
            color: #333333;
            line-height: 1.6;
        }
        .token-box {
            background-color: #f8f8f8;
            border: 1px solid #dddddd;
            border-radius: 4px;
            padding: 16px;
            margin: 24px 0;
            word-break: break-all;
            font-family: monospace;
            font-size: 14px;
            color: #555555;
        }
        .btn {
            display: inline-block;
            background-color: #c0392b;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 4px;
            font-size: 15px;
            margin: 8px 0;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 16px 32px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TOP KEMA Telkom</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $user->nama_depan }} {{ $user->nama_belakang }}</strong>,</p>

            <p>Terima kasih telah mendaftar di sistem TOP KEMA Telkom. Untuk mengaktifkan akun Anda, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>

            <p style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="btn">Verifikasi Email Saya</a>
            </p>

            <p>Atau gunakan token berikut secara manual melalui endpoint <code>POST /api/v1/auth/verify-email</code>:</p>

            <div class="token-box">{{ $token }}</div>

            <p>Token ini akan kedaluwarsa dalam <strong>{{ $expiresInHours }} jam</strong> sejak email ini dikirim.</p>

            <p>Jika Anda tidak mendaftar di sistem ini, abaikan email ini.</p>

            <p>Salam,<br>Tim TOP KEMA Telkom</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} TOP KEMA Telkom. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
