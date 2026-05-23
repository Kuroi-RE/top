<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar &mdash; TOP Telkom Ormawa &amp; Prestasi</title>

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
            width: min(880px, 100%);
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

        /* ── Hero panel ── */
        .login-hero {
            display: none;
            position: relative;
            padding: 1.8rem 2rem;
            background: linear-gradient(160deg, #850b15 0%, #b91c1c 46%, #e11d48 100%);
            color: #fff;
            overflow: hidden;
        }

        .hero-main {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            animation: hero-fadein 0.7s ease both;
        }

        .login-hero::before,
        .login-hero::after {
            content: "";
            position: absolute;
            border-radius: 9999px;
            pointer-events: none;
        }
        .login-hero::before {
            width: 210px; height: 210px;
            top: -100px; right: -70px;
            background: rgba(255,255,255,0.16);
        }
        .login-hero::after {
            width: 130px; height: 130px;
            bottom: -70px; left: -48px;
            background: rgba(255,255,255,0.14);
        }

        .hero-logo {
            width: auto; height: 48px; max-width: 160px;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.16);
            padding: 0.32rem 0.72rem;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .hero-point {
            height: 6px; width: 6px;
            border-radius: 9999px;
            background: #fff;
        }

        /* ── Illustration ── */
        .hero-illustration-wrap {
            margin-top: 0.9rem;
            border-radius: 18px;
            background: linear-gradient(145deg, rgba(255,255,255,0.22), rgba(255,255,255,0.08));
            padding: 0.55rem;
            backdrop-filter: blur(8px);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.28), 0 20px 40px rgba(63,11,15,0.3);
            animation: illus-float 4s ease-in-out infinite;
            display: flex;
            gap: 0.5rem;
            align-items: stretch;
            height: 130px;
        }

        .illus-chart-card {
            flex: 1;
            border-radius: 16px;
            background: linear-gradient(160deg, #fff8f8 0%, #ffeef2 100%);
            box-shadow: 0 2px 0 rgba(255,255,255,0.9) inset, 0 6px 16px rgba(190,18,60,0.14);
            padding: 0.75rem 0.8rem 0;
            display: flex; flex-direction: column; gap: 0.4rem;
            overflow: hidden; position: relative;
        }
        .illus-chart-card::after {
            content: ''; position: absolute; inset: 0;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, rgba(255,255,255,0) 55%);
            pointer-events: none;
        }
        .illus-lines { display: flex; flex-direction: column; gap: 0.28rem; }
        .illus-line { height: 6px; border-radius: 99px; background: #ffd0db; }
        .illus-line:nth-child(1) { width: 75%; animation: shimmer-in 0.5s 0.8s ease both; }
        .illus-line:nth-child(2) { width: 50%; background: #fbcad7; opacity: 0.85; animation: shimmer-in 0.5s 1.0s ease both; }
        .illus-line:nth-child(3) { width: 62%; background: #fbcad7; opacity: 0.65; animation: shimmer-in 0.5s 1.2s ease both; }
        .illus-bars { display: flex; align-items: flex-end; gap: 7px; flex: 1; padding: 0.35rem 0 0; }
        .illus-bar { flex: 1; border-radius: 5px 5px 0 0; background: linear-gradient(175deg, #fb7185 0%, #e11d48 55%, #9f1239 100%); box-shadow: 0 -2px 6px rgba(225,29,72,0.25); }
        .illus-bar:nth-child(1) { height: 42%; opacity: 0.80; animation: bar-grow 0.6s 0.2s  cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }
        .illus-bar:nth-child(2) { height: 62%; opacity: 0.88; animation: bar-grow 0.6s 0.35s cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }
        .illus-bar:nth-child(3) { height: 90%;               animation: bar-grow 0.6s 0.5s  cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }
        .illus-bar:nth-child(4) { height: 55%; opacity: 0.85; animation: bar-grow 0.6s 0.65s cubic-bezier(.34,1.5,.64,1) both; transform-origin: bottom; }

        .illus-trophy-panel {
            width: 102px; flex-shrink: 0; border-radius: 18px;
            background: linear-gradient(145deg, #7c2d12 0%, #7f1d1d 60%, #450a0a 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            position: relative; overflow: visible;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.12), 0 8px 24px rgba(80,10,10,0.45);
        }
        .illus-trophy-panel::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 50%;
            border-radius: 18px 18px 0 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.13) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }
        .illus-trophy-icon {
            font-family: 'Material Symbols Rounded'; font-size: 58px; color: #FBBF24; line-height: 1;
            animation: trophy-sway 2.8s 1s ease-in-out infinite;
            filter: drop-shadow(0 0 10px rgba(251,191,36,0.55)) drop-shadow(0 4px 8px rgba(0,0,0,0.35));
            user-select: none; position: relative; z-index: 1;
        }
        .illus-badge {
            position: absolute; bottom: -6px;
            font-size: 0.5rem; font-weight: 800; letter-spacing: 0.06em; color: #7f1d1d;
            background: linear-gradient(135deg, #fde68a, #fbbf24);
            border-radius: 99px; padding: 2px 7px; box-shadow: 0 2px 6px rgba(0,0,0,0.3); z-index: 2;
        }
        .illus-bubble-big {
            position: absolute; top: -12px; right: -10px; width: 26px; height: 26px;
            border-radius: 9999px; background: radial-gradient(circle at 35% 35%, #fff3b0, #fde68a);
            box-shadow: 0 3px 8px rgba(0,0,0,0.2); opacity: 0.96;
            animation: bubble-pulse 2.6s 0.8s ease-in-out infinite;
        }
        .illus-bubble-small {
            position: absolute; top: 9px; right: 10px; width: 13px; height: 13px;
            border-radius: 9999px; background: radial-gradient(circle at 35% 35%, #ffffff, #fff3b0);
            opacity: 0.9; animation: bubble-pulse 2.6s 1.3s ease-in-out infinite;
        }

        .hero-tag-row {
            position: relative; z-index: 10;
            display: flex; flex-wrap: wrap; align-items: center;
            gap: 0.42rem; margin-top: 0.7rem;
        }
        .credential-tag {
            border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.16);
            padding: 0.18rem 0.62rem;
            font-size: 0.66rem; line-height: 1.35; color: #fff;
        }

        /* ── Keyframes ── */
        @keyframes illus-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        @keyframes hero-fadein { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bar-grow { from { transform: scaleY(0); } to { transform: scaleY(1); } }
        @keyframes trophy-sway { 0%, 100% { transform: rotate(-4deg); } 50% { transform: rotate(4deg); } }
        @keyframes bubble-pulse { 0%, 100% { opacity: 0.95; transform: scale(1); } 50% { opacity: 0.7; transform: scale(1.12); } }
        @keyframes shimmer-in { from { opacity: 0; transform: translateX(-8px); } to { opacity: 1; transform: translateX(0); } }

        .hero-student-wrap {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .hero-student-img {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 160%;
            max-width: none;
            object-fit: contain;
            object-position: bottom center;
            filter: drop-shadow(0 6px 18px rgba(0,0,0,0.28));
            animation: hero-fadein 0.7s ease both;
        }

        /* ── Form panel ── */
        .login-form-panel {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1.8rem 2rem;
        }

        .form-inner {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
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
        .input-modern::placeholder { color: #9ca3af; }
        .input-modern:focus {
            outline: none;
            border-color: rgba(193, 18, 31, 0.65);
            box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.13);
        }

        .field-icon {
            position: absolute;
            right: 0.78rem; top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .form-action {
            width: 100%; border: 0;
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--brand-red) 0%, #ef4444 100%);
            color: #fff; padding: 0.68rem 1rem;
            font-size: 0.88rem; font-weight: 600; letter-spacing: 0.03em;
            box-shadow: 0 10px 20px rgba(193, 18, 31, 0.23);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
            cursor: pointer;
        }
        .form-action:hover { transform: translateY(-1px); filter: brightness(1.02); box-shadow: 0 12px 22px rgba(193,18,31,0.28); }
        .form-action:active { transform: translateY(0); }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
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
            .login-grid { grid-template-columns: 0.9fr 1.1fr; }
            .login-hero {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                gap: 1.2rem;
            }

            /* Shift student illustration further left so the person sits visually
               centered inside the red hero panel (tweak as needed) */
            .hero-student-img {
                left: -22%;
                transform: translateX(0);
                width: 130%;
                object-position: bottom left;
            }
        }
    </style>
</head>

<body class="flex min-h-screen items-center justify-center px-6 py-10 sm:px-10 sm:py-12">

    <div class="login-shell login-grid w-full" style="max-width: 900px;">

        {{-- ── Hero Panel (kiri) ── --}}
        <section class="login-hero">
            <div class="hero-main">
                <div class="mt-6">
                    <h2 class="text-2xl font-bold leading-tight tracking-tight">Bergabung di TOPKEMA</h2>
                    <p class="mt-2 max-w-sm text-xs leading-relaxed text-red-50/90">
                        Daftarkan akun mahasiswa untuk melaporkan prestasi dari satu dashboard yang terintegrasi
                    </p>

                    <div class="hero-illustration-wrap">
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
                        <div class="illus-trophy-panel">
                            <span class="illus-bubble-big"></span>
                            <span class="illus-bubble-small"></span>
                            <span class="illus-trophy-icon">emoji_events</span>
                            <span class="illus-badge">JUARA</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foto mahasiswa di space kosong --}}
            <div class="hero-student-wrap">
                <img src="{{ asset('orangpromo1.png') }}" alt="Mahasiswa TOPKEMA" class="hero-student-img" />
            </div>

            <div class="hero-tag-row">
                <span class="credential-tag">UI Modern</span>
                <span class="credential-tag">Responsif</span>
                <span class="credential-tag">Akses Cepat</span>
            </div>
        </section>

        {{-- ── Form Panel (kanan) ── --}}
        <section class="login-form-panel" id="register-card">
            <div class="form-inner">

            {{-- Mobile: logo + label --}}
            <div class="mb-4 flex items-center justify-between lg:hidden">
                <img src="{{ asset('top_logo.png') }}" alt="TOP Logo" class="w-auto object-contain" style="height: 44px; max-width: 140px;" />
                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">TOPKEMA</span>
            </div>

            <div class="mb-5">
                <h1 class="text-[1.75rem] font-extrabold leading-tight text-gray-900">Daftar Akun</h1>
                <p class="mt-1.5 text-sm text-slate-500">Isi data diri kamu untuk membuat akun mahasiswa</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" id="register-form" novalidate>
                @csrf

                {{-- Nama Lengkap --}}
                <div class="mb-4">
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <div class="relative">
                        <input id="name" name="name" type="text" class="input-modern"
                            placeholder="Nama lengkap kamu"
                            value="{{ old('name') }}" autocomplete="name" autofocus required />
                        <span class="field-icon">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- NIM --}}
                <div class="mb-4">
                    <label for="nim" class="mb-2 block text-sm font-semibold text-slate-700">NIM</label>
                    <div class="relative">
                        <input id="nim" name="nim" type="text" class="input-modern"
                            placeholder="Nomor Induk Mahasiswa"
                            value="{{ old('nim') }}" required />
                        <span class="field-icon">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12zM6 10h2v2H6zm0 4h2v2H6zm4-4h8v2h-8zm0 4h5v2h-5z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Program Studi --}}
                <div class="mb-4">
                    <label for="prodi" class="mb-2 block text-sm font-semibold text-slate-700">Program Studi</label>
                    <div class="relative">
                        <select id="prodi" name="prodi"
                            style="-webkit-appearance:none; -moz-appearance:none; appearance:none;"
                            class="input-modern cursor-pointer bg-white">
                            <option value="" disabled {{ old('prodi') ? '' : 'selected' }}>Pilih program studi</option>
                            <option value="S1 Teknik Telekomunikasi" {{ old('prodi') == 'S1 Teknik Telekomunikasi' ? 'selected' : '' }}>S1 Teknik Telekomunikasi</option>
                            <option value="S1 Teknik Elektro" {{ old('prodi') == 'S1 Teknik Elektro' ? 'selected' : '' }}>S1 Teknik Elektro</option>
                            <option value="S1 Teknik Biomedis" {{ old('prodi') == 'S1 Teknik Biomedis' ? 'selected' : '' }}>S1 Teknik Biomedis</option>
                            <option value="S1 Teknologi Pangan" {{ old('prodi') == 'S1 Teknologi Pangan' ? 'selected' : '' }}>S1 Teknologi Pangan</option>
                            <option value="S1 Teknik Industri" {{ old('prodi') == 'S1 Teknik Industri' ? 'selected' : '' }}>S1 Teknik Industri</option>
                            <option value="S1 Teknik Logistik" {{ old('prodi') == 'S1 Teknik Logistik' ? 'selected' : '' }}>S1 Teknik Logistik</option>
                            <option value="S1 Teknik Informatika" {{ old('prodi') == 'S1 Teknik Informatika' ? 'selected' : '' }}>S1 Teknik Informatika</option>
                            <option value="S1 Sistem Informasi" {{ old('prodi') == 'S1 Sistem Informasi' ? 'selected' : '' }}>S1 Sistem Informasi</option>
                            <option value="S1 Rekayasa Perangkat Lunak" {{ old('prodi') == 'S1 Rekayasa Perangkat Lunak' ? 'selected' : '' }}>S1 Rekayasa Perangkat Lunak</option>
                            <option value="S1 Sains Data" {{ old('prodi') == 'S1 Sains Data' ? 'selected' : '' }}>S1 Sains Data</option>
                            <option value="S1 Desain Komunikasi Visual (DKV)" {{ old('prodi') == 'S1 Desain Komunikasi Visual (DKV)' ? 'selected' : '' }}>S1 Desain Komunikasi Visual (DKV)</option>
                            <option value="S1 Desain Produk" {{ old('prodi') == 'S1 Desain Produk' ? 'selected' : '' }}>S1 Desain Produk</option>
                            <option value="S1 Bisnis Digital" {{ old('prodi') == 'S1 Bisnis Digital' ? 'selected' : '' }}>S1 Bisnis Digital</option>
                            <option value="D3 Teknik Telekomunikasi" {{ old('prodi') == 'D3 Teknik Telekomunikasi' ? 'selected' : '' }}>D3 Teknik Telekomunikasi</option>
                        </select>
                        <span class="field-icon pointer-events-none">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M7 10l5 5 5-5z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <div class="relative">
                        <input id="email" name="email" type="email" class="input-modern"
                            placeholder="email@student.telkomuniversity.ac.id"
                            value="{{ old('email') }}" autocomplete="email" required />
                        <span class="field-icon">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" class="input-modern"
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password" required />
                        <button type="button" onclick="togglePasswordVisibility('password', 'icon-pass')"
                            class="field-icon bg-transparent border-0 p-0 cursor-pointer hover:text-slate-600 transition-colors focus:outline-none"
                            aria-label="Tampilkan / sembunyikan password" tabindex="-1">
                            <span id="icon-pass" class="material-symbols-rounded" style="font-size:20px;">visibility_off</span>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-5">
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" class="input-modern"
                            placeholder="Ulangi password kamu"
                            autocomplete="new-password" required />
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'icon-confirm')"
                            class="field-icon bg-transparent border-0 p-0 cursor-pointer hover:text-slate-600 transition-colors focus:outline-none"
                            aria-label="Tampilkan / sembunyikan konfirmasi password" tabindex="-1">
                            <span id="icon-confirm" class="material-symbols-rounded" style="font-size:20px;">visibility_off</span>
                        </button>
                    </div>
                </div>

                <button type="submit" id="register-btn" class="form-action disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg id="btn-spinner" class="mr-2 h-4 w-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span id="btn-label">Daftar</span>
                </button>

                <p class="mt-3 text-center text-xs text-slate-500">
                    Sudah punya akun?
                    <a href="{{ url('/login') }}" class="font-semibold text-red-700 hover:text-red-800 transition-colors">Masuk Sekarang</a>
                </p>

            </form>

            </div>{{-- /form-inner --}}
        </section>
    </div>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }

        document.getElementById('register-form').addEventListener('submit', function (e) {
            const name  = document.getElementById('name').value.trim();
            const nim   = document.getElementById('nim').value.trim();
            const email = document.getElementById('email').value.trim();
            const pass  = document.getElementById('password').value;

            if (!name || !nim || !email || !pass) {
                const card = document.getElementById('register-card');
                card.classList.remove('shake');
                void card.offsetWidth;
                card.classList.add('shake');
                e.preventDefault();
                return;
            }

            const btn     = document.getElementById('register-btn');
            const spinner = document.getElementById('btn-spinner');
            const label   = document.getElementById('btn-label');
            btn.disabled      = true;
            spinner.classList.remove('hidden');
            label.textContent = 'Memproses...';
        });

        document.getElementById('register-card').addEventListener('animationend', function () {
            this.classList.remove('shake');
        });
    </script>

</body>
</html>
