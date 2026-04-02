<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login &mdash; TOP Telkom Ormawa &amp; Prestasi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Prevent Chrome autofill from overriding background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
            box-shadow: 0 0 0 1000px #ffffff inset;
            -webkit-text-fill-color: #111827;
            transition: background-color 9999s ease-in-out 0s;
        }

        .input-underline {
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #1f2937;
            border-radius: 0;
            outline: none;
            width: 100%;
            padding: 0.375rem 0;
            font-size: 0.9375rem;
            color: #111827;
            transition: border-color 0.2s ease;
        }
        .input-underline::placeholder { color: #9ca3af; }
        .input-underline:focus { border-bottom-color: #b91c1c; }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%       { transform: translateX(-8px); }
            40%       { transform: translateX(8px); }
            60%       { transform: translateX(-5px); }
            80%       { transform: translateX(5px); }
        }
        .shake { animation: shake 0.4s ease; }
    </style>
</head>

<body class="flex min-h-screen items-center justify-center bg-gray-100 px-4 py-10">

    <div class="flex w-full max-w-sm flex-col items-center">

        <div class="mb-5 flex flex-col items-center select-none">
            <svg width="120" height="96" viewBox="0 0 120 96" fill="none"
                 xmlns="http://www.w3.org/2000/svg" aria-label="TOP Logo">
                <defs>
                    <filter id="doc-drop" x="-15%" y="-10%" width="140%" height="135%">
                        <feDropShadow dx="1" dy="3" stdDeviation="3" flood-color="rgba(0,0,0,0.18)"/>
                    </filter>
                </defs>
                <rect x="18" y="8" width="68" height="80" rx="5" fill="white" filter="url(#doc-drop)"/>
                <rect x="36" y="4"   width="32" height="11" rx="3.5" fill="#d1d5db"/>
                <rect x="39" y="5.5" width="26" height="8"  rx="2.5" fill="#e9ebee"/>
                <rect x="28" y="34" width="48" height="4" rx="2" fill="#e5e7eb"/>
                <rect x="28" y="46" width="48" height="4" rx="2" fill="#e5e7eb"/>
                <rect x="28" y="58" width="36" height="4" rx="2" fill="#e5e7eb"/>
                <circle cx="80" cy="22" r="17" fill="#b91c1c"/>
                <path d="M72 22 L78 28 L90 14"
                      stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            <div class="relative mt-1">
                <span class="block text-4xl font-black italic leading-none tracking-wider text-red-700">TOP</span>
                <span class="absolute -bottom-1 left-0 right-0 block h-1 w-full rounded-full bg-red-700"></span>
            </div>
        </div>

        <p class="mt-3 text-center text-base text-gray-700 leading-snug">Hallo! Selamat Datang di</p>
        <p class="text-center text-lg font-extrabold text-gray-900 leading-snug">Telkom Ormawa &amp; Prestasi</p>

        <div class="mt-7 w-full rounded-3xl bg-white px-9 py-9 shadow-lg" id="login-card">

            <h1 class="mb-8 text-center text-2xl font-extrabold uppercase tracking-widest text-gray-900">
                SSO LOGIN
            </h1>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" id="login-form" novalidate>
                @csrf

                <div class="mb-7">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-800" fill="currentColor"
                             viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12
                                     12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            class="input-underline"
                            placeholder="manggala"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            autofocus
                            required
                        />
                    </div>
                </div>

                <div class="mb-9">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-800" fill="currentColor"
                             viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9
                                     2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2
                                     2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39
                                     3.1 3.1v2z"/>
                        </svg>
                        <div class="relative flex-1">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="input-underline pr-8"
                                placeholder="Password"
                                autocomplete="current-password"
                                required
                            />
                            <button
                                type="button"
                                onclick="togglePasswordVisibility()"
                                class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-400
                                       hover:text-gray-600 transition-colors focus:outline-none"
                                aria-label="Tampilkan / sembunyikan password"
                                tabindex="-1"
                            >
                                <svg id="icon-eye-slash" class="h-5 w-5"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97
                                             9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242
                                             4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0
                                             0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0
                                             01-4.132 5.411m0 0L21 21"/>
                                </svg>
                                <svg id="icon-eye" class="h-5 w-5 hidden"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542
                                             7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    id="login-btn"
                    class="flex w-full items-center justify-center rounded-full bg-red-700 py-3.5
                           text-base font-semibold lowercase tracking-wide text-white shadow-md
                           hover:bg-red-800 active:bg-red-900 focus:outline-none focus:ring-2
                           focus:ring-red-500 focus:ring-offset-2 transition-all duration-200
                           disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <svg id="btn-spinner" class="mr-2 h-4 w-4 animate-spin hidden"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span id="btn-label">login</span>
                </button>

            </form>

        </div>

    </div>

    <script>
        function togglePasswordVisibility() {
            const input     = document.getElementById('password');
            const iconSlash = document.getElementById('icon-eye-slash');
            const iconEye   = document.getElementById('icon-eye');

            if (input.type === 'password') {
                input.type = 'text';
                iconSlash.classList.add('hidden');
                iconEye.classList.remove('hidden');
            } else {
                input.type = 'password';
                iconSlash.classList.remove('hidden');
                iconEye.classList.add('hidden');
            }
        }

        document.getElementById('login-form').addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username || !password) {
                const card = document.getElementById('login-card');
                card.classList.remove('shake');
                void card.offsetWidth; // force reflow to restart animation
                card.classList.add('shake');
                e.preventDefault();
                return;
            }

            const btn     = document.getElementById('login-btn');
            const spinner = document.getElementById('btn-spinner');
            const label   = document.getElementById('btn-label');
            btn.disabled      = true;
            spinner.classList.remove('hidden');
            label.textContent = 'memproses...';
        });

        document.getElementById('login-card').addEventListener('animationend', function () {
            this.classList.remove('shake');
        });
    </script>

</body>
</html>
