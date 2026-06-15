<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lupa Password &mdash; TOP Telkom Ormawa &amp; Prestasi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,1,0&icon_names=emoji_events,visibility,visibility_off" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-red: #c1121f;
            --line: #dbe1ea;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at 8% 12%, rgba(255, 214, 214, 0.8) 0, rgba(255, 214, 214, 0) 38%),
                radial-gradient(circle at 92% 84%, rgba(252, 211, 77, 0.35) 0, rgba(252, 211, 77, 0) 36%),
                linear-gradient(145deg, #f8fafc 0%, #eef2ff 48%, #fef2f2 100%);
        }

        .login-shell {
            width: min(860px, 100%);
            border-radius: 22px;
            border: 1px solid rgba(148, 163, 184, 0.22);
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(8px);
            box-shadow: 0 22px 56px rgba(15, 23, 42, 0.13);
            overflow: hidden;
        }

        .login-grid {
            display: grid;
            grid-template-columns: 1fr;
        }

        .login-hero {
            display: none;
            position: relative;
            padding: 2.5rem;
            background: linear-gradient(160deg, #850b15 0%, #b91c1c 46%, #e11d48 100%);
            color: #fff;
            overflow: hidden;
        }

        .hero-main {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .hero-logo {
            width: auto;
            height: 48px;
            max-width: 160px;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .login-hero::before,
        .login-hero::after {
            content: "";
            position: absolute;
            border-radius: 9999px;
            pointer-events: none;
        }

        .login-hero::before {
            width: 210px;
            height: 210px;
            top: -100px;
            right: -70px;
            background: rgba(255, 255, 255, 0.16);
        }

        .login-hero::after {
            width: 130px;
            height: 130px;
            bottom: -70px;
            left: -48px;
            background: rgba(255, 255, 255, 0.14);
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.16);
            padding: 0.32rem 0.72rem;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .hero-point {
            height: 6px;
            width: 6px;
            border-radius: 9999px;
            background: #fff;
        }

        .hero-illustration-wrap {
            margin-top: 1.2rem;
            border-radius: 22px;
            background: linear-gradient(145deg, rgba(255,255,255,0.22), rgba(255,255,255,0.08));
            padding: 0.65rem;
            backdrop-filter: blur(8px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.28),
                0 20px 40px rgba(63,11,15,0.3);
            animation: illus-float 4s ease-in-out infinite;
            display: flex;
            gap: 0.55rem;
            align-items: stretch;
            height: 160px;
        }

        .illus-chart-card {
            flex: 1;
            border-radius: 16px;
            background: linear-gradient(160deg, #fff8f8 0%, #ffeef2 100%);
            box-shadow:
                0 2px 0 rgba(255,255,255,0.9) inset,
                0 6px 16px rgba(190,18,60,0.14);
            padding: 0.75rem 0.8rem 0;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            overflow: hidden;
            position: relative;
        }

        .illus-chart-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, rgba(255,255,255,0) 55%);
            pointer-events: none;
        }

        .illus-bars-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            border-top: 1px dashed rgba(251,161,183,0.5);
            position: relative;
        }

        .illus-lines { display: flex; flex-direction: column; gap: 0.28rem; }
        .illus-line {
            height: 6px;
            border-radius: 99px;
            background: #ffd0db;
        }
        .illus-line:nth-child(1) { width: 75%; animation: shimmer-in 0.5s 0.8s ease both; }
        .illus-line:nth-child(2) { width: 50%; background: #fbcad7; opacity: 0.85; animation: shimmer-in 0.5s 1.0s ease both; }
        .illus-line:nth-child(3) { width: 62%; background: #fbcad7; opacity: 0.65; animation: shimmer-in 0.5s 1.2s ease both; }

        .illus-bars {
            display: flex;
            align-items: flex-end;
            gap: 7px;
            flex: 1;
            padding: 0.35rem 0 0;
        }

        .illus-bar {
            flex: 1;
            border-radius: 5px 5px 0 0;
            background: linear-gradient(175deg, #fb7185 0%, #e11d48 55%, #9f1239 100%);
            box-shadow: 0 -2px 6px rgba(225,29,72,0.25);
        }
        .illus-bar:nth-child(1) { height: 42%; opacity: 0.80; animation: bar-grow 0.6s 0.2s  cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }
        .illus-bar:nth-child(2) { height: 62%; opacity: 0.88; animation: bar-grow 0.6s 0.35s cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }
        .illus-bar:nth-child(3) { height: 90%;               animation: bar-grow 0.6s 0.5s  cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }
        .illus-bar:nth-child(4) { height: 55%; opacity: 0.85; animation: bar-grow 0.6s 0.65s cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }

        .illus-trophy-panel {
            width: 102px;
            flex-shrink: 0;
            border-radius: 18px;
            background: linear-gradient(145deg, #7c2d12 0%, #7f1d1d 60%, #450a0a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: visible;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 8px 24px rgba(80,10,10,0.45);
        }

        .illus-trophy-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 50%;
            border-radius: 18px 18px 0 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.13) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .illus-trophy-icon {
            font-family: 'Material Symbols Rounded';
            font-size: 58px;
            color: #FBBF24;
            line-height: 1;
            animation: trophy-sway 2.8s 1s ease-in-out infinite;
            filter:
                drop-shadow(0 0 10px rgba(251,191,36,0.55))
                drop-shadow(0 4px 8px rgba(0,0,0,0.35));
            user-select: none;
            position: relative;
            z-index: 1;
        }

        .illus-badge {
            position: absolute;
            bottom: -6px;
            font-size: 0.5rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            color: #7f1d1d;
            background: linear-gradient(135deg, #fde68a, #fbbf24);
            border-radius: 99px;
            padding: 2px 7px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            z-index: 2;
        }

        .illus-bubble-big {
            position: absolute;
            top: -12px;
            right: -10px;
            width: 26px;
            height: 26px;
            border-radius: 9999px;
            background: radial-gradient(circle at 35% 35%, #fff3b0, #fde68a);
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            opacity: 0.96;
            animation: bubble-pulse 2.6s 0.8s ease-in-out infinite;
        }
        .illus-bubble-small {
            position: absolute;
            top: 9px;
            right: 10px;
            width: 13px;
            height: 13px;
            border-radius: 9999px;
            background: radial-gradient(circle at 35% 35%, #ffffff, #fff3b0);
            opacity: 0.9;
            animation: bubble-pulse 2.6s 1.3s ease-in-out infinite;
        }

        .hero-main {
            animation: hero-fadein 0.7s ease both;
        }

        @keyframes illus-float {
            0%, 100% { transform: translateY(0px);    }
            50%       { transform: translateY(-7px); }
        }

        @keyframes hero-fadein {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes bar-grow {
            from { transform: scaleY(0); }
            to   { transform: scaleY(1); }
        }

        @keyframes trophy-sway {
            0%, 100% { transform: rotate(-4deg); }
            50%       { transform: rotate(4deg); }
        }

        @keyframes bubble-pulse {
            0%, 100% { opacity: 0.95; transform: scale(1);    }
            50%       { opacity: 0.7;  transform: scale(1.12); }
        }

        @keyframes shimmer-in {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .hero-tag-row {
            position: relative;
            z-index: 10;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.42rem;
            margin-top: 0.7rem;
        }

        .credential-tag {
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.16);
            padding: 0.18rem 0.62rem;
            font-size: 0.66rem;
            line-height: 1.35;
            color: #fff;
        }

        .login-form-panel {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 1.55rem 1.6rem;
        }

        .input-modern {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            padding: 0.62rem 2.6rem 0.62rem 0.9rem;
            font-size: 0.92rem;
            color: #111827;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-modern::placeholder {
            color: #9ca3af;
        }

        .input-modern:focus {
            outline: none;
            border-color: rgba(193, 18, 31, 0.65);
            box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.13);
        }

        .field-icon {
            position: absolute;
            right: 0.78rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .form-action {
            width: 100%;
            border: 0;
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--brand-red) 0%, #ef4444 100%);
            color: #fff;
            padding: 0.68rem 1rem;
            font-size: 0.88rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            box-shadow: 0 10px 20px rgba(193, 18, 31, 0.23);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .form-action:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
            box-shadow: 0 12px 22px rgba(193, 18, 31, 0.28);
        }

        .form-action:active {
            transform: translateY(0);
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
            box-shadow: 0 0 0 1000px #ffffff inset;
            -webkit-text-fill-color: #111827;
            transition: background-color 9999s ease-in-out 0s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        .shake { animation: shake 0.4s ease; }

        @media (min-width: 1024px) {
            .login-grid {
                grid-template-columns: 0.9fr 1.1fr;
            }

            .login-hero {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body class="flex min-h-screen items-center justify-center px-4 py-8 sm:py-10">

    <div class="login-shell login-grid w-full">

        <section class="login-hero">
            <div class="hero-main">
                <div class="mt-6">
                    <h2 class="text-3xl font-bold leading-tight tracking-tight">Lupa Password<br>TOPKEMA?</h2>
                    <p class="mt-3 max-w-sm text-xs leading-relaxed text-red-50/90">
                        Jangan khawatir, masukkan email Anda untuk mendapatkan tautan verifikasi pemulihan password akun Anda.
                    </p>

                    <div class="hero-illustration-wrap">
                        <!-- Bar chart panel -->
                        <div class="illus-chart-card">
                            <div class="illus-lines">
                                <div class="illus-line"></div>
                                <div class="illus-line"></div>
                                <div class="illus-line"></div>
                            </div>
                            <div class="illus-bars">
                                <div class="illus-bar"></div>
                                <div class="illus-bar"></div>
                                <div class="illus-bar"></div>
                                <div class="illus-bar"></div>
                            </div>
                        </div>

                        <!-- Trophy panel -->
                        <div class="illus-trophy-panel">
                            <span class="illus-bubble-big"></span>
                            <span class="illus-bubble-small"></span>
                            <span class="illus-trophy-icon">emoji_events</span>
                            <span class="illus-badge">JUARA</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-tag-row">
                <span class="credential-tag">Aman</span>
                <span class="credential-tag">Instan</span>
                <span class="credential-tag">Mudah</span>
            </div>
        </section>

        <section class="login-form-panel" id="forgot-card">
            <div class="mb-4 flex items-center justify-between lg:hidden">
                <img src="{{ asset('top_logo.png') }}" alt="TOP Logo" class="w-auto object-contain" style="height: 44px; max-width: 140px;" />
                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">TOPKEMA</span>
            </div>

            <div class="mb-5">
                <h1 class="text-[1.75rem] font-extrabold leading-tight text-gray-900">Lupa Password</h1>
                <p class="mt-1.5 text-sm text-slate-500">Masukkan email terdaftar Anda untuk menerima link reset password.</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgot-form" novalidate>
                @csrf

                <div class="mb-5">
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Email</label>
                    <div class="relative">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="input-modern"
                            placeholder="nama@student.telkomuniversity.ac.id"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            autofocus
                            required
                        />
                        <span class="field-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <button
                    type="submit"
                    id="submit-btn"
                    class="form-action disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <svg id="btn-spinner" class="mr-2 h-4 w-4 animate-spin hidden"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span id="btn-label">Kirim Link Reset</span>
                </button>

                <p class="mt-4 text-center text-xs text-slate-500">
                    <a href="{{ route('login') }}" class="font-semibold text-red-700 hover:text-red-800 transition-colors">Kembali ke Halaman Masuk</a>
                </p>

            </form>
        </section>
    </div>

    <script>
        document.getElementById('forgot-form').addEventListener('submit', function (e) {
            const email = document.getElementById('email').value.trim();

            if (!email) {
                const card = document.getElementById('forgot-card');
                card.classList.remove('shake');
                void card.offsetWidth; // force reflow
                card.classList.add('shake');
                e.preventDefault();
                return;
            }

            const btn     = document.getElementById('submit-btn');
            const spinner = document.getElementById('btn-spinner');
            const label   = document.getElementById('btn-label');
            btn.disabled      = true;
            spinner.classList.remove('hidden');
            label.textContent = 'Mengirim...';
        });

        document.getElementById('forgot-card').addEventListener('animationend', function () {
            this.classList.remove('shake');
        });
    </script>

</body>
</html>
