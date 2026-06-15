<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi 2FA | TOPKEMA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --brand-red: #c1121f;
            --brand-red-dark: #8f0d16;
            --bg-cream: #fff8f3;
            --line: rgba(148, 163, 184, 0.25);
        }

        body {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(255, 223, 226, 0.9), transparent 32%),
                radial-gradient(circle at bottom right, rgba(193, 18, 31, 0.11), transparent 28%),
                linear-gradient(180deg, #fffefc 0%, var(--bg-cream) 100%);
        }

        .shell {
            max-width: 560px;
            width: 100%;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.75);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
        }

        .hero {
            background: linear-gradient(180deg, var(--brand-red) 0%, var(--brand-red-dark) 100%);
            color: #fff;
            padding: 2rem 2rem 1.5rem;
        }

        .panel {
            padding: 1.8rem 2rem 2rem;
        }

        .input-modern {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
            padding: 0.9rem 1rem;
            font-size: 1rem;
            letter-spacing: 0.35em;
            text-align: center;
            color: #111827;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-modern:focus {
            outline: none;
            border-color: rgba(193, 18, 31, 0.7);
            box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.12);
        }

        .action {
            width: 100%;
            border: 0;
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--brand-red) 0%, #ef4444 100%);
            color: #fff;
            padding: 0.8rem 1rem;
            font-size: 0.92rem;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(193, 18, 31, 0.23);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .action:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(193, 18, 31, 0.28);
        }
    </style>
</head>
<body class="min-h-screen px-4 py-10 flex items-center justify-center">
    <div class="shell">
        <section class="hero">
            <p class="text-xs uppercase tracking-[0.3em] text-red-100/80">2FA Verification</p>
            <h1 class="mt-3 text-3xl font-extrabold leading-tight">Masukkan kode verifikasi</h1>
            <p class="mt-3 text-sm leading-relaxed text-red-50/90">
                Kode 6 digit sudah dikirim ke {{ $email ?? 'email Anda' }} dan berlaku selama 10 menit.
            </p>
        </section>

        <section class="panel">
            @if (session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('twofactor.verify.post') }}" novalidate>
                @csrf

                <div class="mb-5">
                    <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">Kode Verifikasi</label>
                    <input
                        id="code"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        maxlength="6"
                        class="input-modern"
                        placeholder="123456"
                        value="{{ old('code') }}"
                        autofocus
                        required
                    />
                </div>

                <button type="submit" class="action">Verifikasi</button>
            </form>

            <form method="POST" action="{{ route('twofactor.verify.resend') }}" class="mt-3">
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-700 transition-colors hover:bg-red-50"
                >
                    Kirim ulang kode
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-slate-500">
                Jika kode belum masuk, cek folder spam atau ulangi login/register.
            </p>

            <p class="mt-3 text-center text-xs text-slate-500">
                <a href="{{ url('/login') }}" class="font-semibold text-red-700 hover:text-red-800">Kembali ke login</a>
            </p>
        </section>
    </div>
</body>
</html>