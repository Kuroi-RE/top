<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TOP') }} &mdash; @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body { font-family: 'Inter', sans-serif; }

        #sidebar {
            transition: width 0.3s ease-in-out;
            overflow: hidden;
            min-height: 0;
            width: 16rem;
        }

        .acc-panel {
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        #sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 9999px; }
    </style>
</head>

<body class="bg-slate-100 antialiased">

<div class="flex h-screen flex-col overflow-hidden">

    <header class="z-40 flex h-16 flex-shrink-0 items-center justify-between bg-white px-6 shadow-sm">

        <a href="{{ url('/') }}" class="flex select-none items-center gap-2">
            <svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect x="5" y="9" width="34" height="29" rx="3" fill="#f9fafb" stroke="#d1d5db" stroke-width="1.5"/>
                <rect x="15" y="5" width="14" height="8" rx="2.5" fill="#d1d5db"/>
                <rect x="17" y="6.5" width="10" height="5" rx="1.5" fill="#f3f4f6"/>
                <path d="M13 23 L19 29 L31 15" stroke="#dc2626" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-2xl font-extrabold tracking-widest text-red-600 leading-none">TOP</span>
        </a>

        <div class="relative" id="user-wrapper">
            <button
                id="user-btn"
                onclick="toggleUserMenu()"
                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
                aria-haspopup="true"
                aria-expanded="false"
            >
                @auth
                    {{ Auth::user()->name }}
                @else
                    manggala
                @endauth
                <svg id="user-chevron" class="h-4 w-4 text-gray-500 transition-transform duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div
                id="user-dropdown"
                class="absolute right-0 z-50 mt-1 w-52 origin-top-right rounded-xl border border-gray-100
                       bg-white py-1 shadow-xl opacity-0 scale-95 pointer-events-none transition-all duration-200"
                role="menu"
            >
                @auth
                <div class="border-b border-gray-100 px-4 py-3">
                    <p class="truncate text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-gray-400 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
                @endauth

                <a href="#"
                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition-colors"
                   role="menuitem">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>

                <div class="my-1 border-t border-gray-100"></div>

                @auth
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors"
                        role="menuitem"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
                @endauth
            </div>
        </div>

    </header>

    <div class="flex flex-1 overflow-hidden">

        @php
            // Ganti dengan Auth::user()->role setelah implementasi autentikasi
            $userRole = 'admin';
        @endphp

        <aside id="sidebar" class="flex-shrink-0 bg-slate-200 flex flex-col shadow-md z-30">
            <nav id="sidebar-nav" class="flex-1 overflow-y-auto overflow-x-hidden py-2" style="min-width: 16rem;">

                @if (in_array($userRole, ['admin', 'kemahasiswaan', 'ketua_institusi', 'ketua_prodi', 'mahasiswa']))
                <div>
                    <button
                        onclick="toggleAcc('acc-organisasi')"
                        class="flex w-full items-center gap-3 bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition-colors"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Organisasi Mahasiswa</span>
                        <svg id="chevron-acc-organisasi"
                             class="h-4 w-4 flex-shrink-0 transition-transform duration-200 rotate-180"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="acc-organisasi" class="acc-panel" style="max-height: none; overflow: visible;" data-open="true">

                        @if (in_array($userRole, ['admin', 'kemahasiswaan', 'ketua_institusi']))
                        <div>
                            <button
                                onclick="toggleAcc('acc-ormawa-institusi')"
                                class="flex w-full items-center gap-3 bg-gray-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-gray-700 transition-colors"
                            >
                                <span class="flex-1 whitespace-nowrap text-left">Ormawa Institusi</span>
                                <svg id="chevron-acc-ormawa-institusi"
                                     class="h-4 w-4 flex-shrink-0 transition-transform duration-200 rotate-180"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="acc-ormawa-institusi" class="acc-panel" style="max-height: none; overflow: visible;" data-open="true">
                                <a
                                    href="{{ url('/organisasi') }}"
                                    class="flex items-center whitespace-nowrap px-8 py-2.5 text-sm font-medium text-white transition-colors
                                           {{ Request::is('organisasi') || Request::is('organisasi/') ? 'bg-gray-800' : 'bg-gray-700 hover:bg-gray-800' }}"
                                >
                                    Beranda
                                </a>

                                <div>
                                    <button
                                        onclick="toggleAcc('acc-input')"
                                        class="flex w-full items-center gap-3 bg-gray-700 px-8 py-2.5 text-sm font-medium text-gray-200 hover:bg-gray-800 transition-colors"
                                    >
                                        <span class="flex-1 whitespace-nowrap text-left">Input</span>
                                        <svg id="chevron-acc-input"
                                             class="h-3.5 w-3.5 flex-shrink-0 transition-transform duration-200"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                    <div id="acc-input" class="acc-panel" style="max-height: 0;" data-open="false">
                                        <a href="#" class="flex items-center whitespace-nowrap bg-gray-800 pl-12 pr-6 py-2 text-xs text-gray-300 hover:bg-gray-900 hover:text-white border-l-2 border-gray-600 transition-colors">
                                            Proposal Kegiatan
                                        </a>
                                        <a href="#" class="flex items-center whitespace-nowrap bg-gray-800 pl-12 pr-6 py-2 text-xs text-gray-300 hover:bg-gray-900 hover:text-white border-l-2 border-gray-600 transition-colors">
                                            LPJ Keuangan
                                        </a>
                                        <a href="#" class="flex items-center whitespace-nowrap bg-gray-800 pl-12 pr-6 py-2 text-xs text-gray-300 hover:bg-gray-900 hover:text-white border-l-2 border-gray-600 transition-colors">
                                            LPJ Kegiatan
                                        </a>
                                    </div>
                                </div>

                                <a href="#" class="flex items-center whitespace-nowrap bg-gray-700 px-8 py-2.5 text-sm font-medium text-gray-200 hover:bg-gray-800 hover:text-white transition-colors">
                                    Template
                                </a>
                            </div>
                        </div>
                        @endif

                        @if (in_array($userRole, ['admin', 'kemahasiswaan', 'ketua_prodi']))
                        <div>
                            <button
                                onclick="toggleAcc('acc-ormawa-prodi')"
                                class="flex w-full items-center gap-3 bg-gray-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-gray-600 transition-colors"
                            >
                                <span class="flex-1 whitespace-nowrap text-left">Ormawa Prodi</span>
                                <svg id="chevron-acc-ormawa-prodi"
                                     class="h-4 w-4 flex-shrink-0 transition-transform duration-200"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <div id="acc-ormawa-prodi" class="acc-panel" style="max-height: 0;" data-open="false">
                                <a href="#" class="flex items-center whitespace-nowrap bg-gray-600 px-8 py-2.5 text-sm text-gray-100 hover:bg-gray-700 hover:text-white transition-colors">Beranda</a>
                                <a href="#" class="flex items-center whitespace-nowrap bg-gray-600 px-8 py-2.5 text-sm text-gray-100 hover:bg-gray-700 hover:text-white transition-colors">Input</a>
                                <a href="#" class="flex items-center whitespace-nowrap bg-gray-600 px-8 py-2.5 text-sm text-gray-100 hover:bg-gray-700 hover:text-white transition-colors">Template</a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
                @endif

                <div>
                    <button
                        onclick="toggleAcc('acc-prestasi')"
                        class="flex w-full items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-slate-300 transition-colors"
                    >
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Prestasi Mahasiswa</span>
                        <svg id="chevron-acc-prestasi"
                             class="h-4 w-4 flex-shrink-0 text-gray-500 transition-transform duration-200"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <div id="acc-prestasi" class="acc-panel" style="max-height: 0;" data-open="false">
                        <a href="{{ url('/prestasi') }}"
                           class="flex items-center whitespace-nowrap bg-gray-600 px-8 py-2.5 text-sm text-white hover:bg-gray-700 transition-colors
                                  {{ Request::is('prestasi') || Request::is('prestasi/') ? 'bg-gray-800' : '' }}">
                            Daftar Prestasi
                        </a>
                        @if (in_array($userRole, ['admin', 'kemahasiswaan', 'mahasiswa']))
                        <a href="{{ url('/prestasi/create') }}"
                           class="flex items-center whitespace-nowrap bg-gray-600 px-8 py-2.5 text-sm text-white hover:bg-gray-700 transition-colors">
                            Input Prestasi
                        </a>
                        @endif
                    </div>
                </div>

            </nav>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-hidden">

            <button
                id="sidebar-toggle-btn"
                onclick="toggleSidebar()"
                class="absolute left-0 top-3 z-50 flex h-7 w-7 items-center justify-center rounded-r-md bg-red-600 text-white shadow-md hover:bg-red-700 transition-colors"
                aria-label="Sembunyikan / Tampilkan sidebar"
            >
                <svg id="icon-close" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <svg id="icon-open" class="h-3.5 w-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <main class="flex-1 overflow-y-auto overflow-x-hidden px-5 pb-5 pt-12 sm:px-6 sm:pt-12" id="main-content">

                @if (session('success'))
                <div id="flash-success"
                     class="mb-4 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
                    <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button onclick="this.closest('#flash-success').remove()" class="text-green-500 hover:text-green-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div id="flash-error"
                     class="mb-4 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                    <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button onclick="this.closest('#flash-error').remove()" class="text-red-500 hover:text-red-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endif

                @yield('content')

            </main>

            <footer class="flex-shrink-0 border-t border-gray-200 bg-white py-3 text-center">
                <p class="text-xs text-gray-500">TOP&copy; 2026 Kemahasiswaan Telkom University Purwokerto</p>
            </footer>

        </div>

    </div>

</div>

<script>
    let _userOpen = false;

    function toggleUserMenu() {
        _userOpen = !_userOpen;
        const dd      = document.getElementById('user-dropdown');
        const chevron = document.getElementById('user-chevron');
        const btn     = document.getElementById('user-btn');

        if (_userOpen) {
            dd.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            dd.classList.add('opacity-100', 'scale-100');
            chevron.style.transform = 'rotate(180deg)';
            btn.setAttribute('aria-expanded', 'true');
        } else {
            _closeUserMenu();
        }
    }

    function _closeUserMenu() {
        _userOpen = false;
        const dd      = document.getElementById('user-dropdown');
        const chevron = document.getElementById('user-chevron');
        const btn     = document.getElementById('user-btn');
        dd.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        dd.classList.remove('opacity-100', 'scale-100');
        chevron.style.transform = '';
        btn.setAttribute('aria-expanded', 'false');
    }

    document.addEventListener('click', (e) => {
        const wrapper = document.getElementById('user-wrapper');
        if (wrapper && !wrapper.contains(e.target) && _userOpen) _closeUserMenu();
    });

    let _sidebarOpen = true;

    function toggleSidebar() {
        _sidebarOpen = !_sidebarOpen;
        const sidebar   = document.getElementById('sidebar');
        const iconClose = document.getElementById('icon-close');
        const iconOpen  = document.getElementById('icon-open');

        if (_sidebarOpen) {
            sidebar.style.width = '16rem';
            iconClose.classList.remove('hidden');
            iconOpen.classList.add('hidden');
        } else {
            sidebar.style.width = '0';
            iconClose.classList.add('hidden');
            iconOpen.classList.remove('hidden');
        }
    }

    function toggleAcc(id) {
        const el      = document.getElementById(id);
        const chevron = document.getElementById('chevron-' + id);
        const isOpen  = el.dataset.open === 'true';

        if (isOpen) {
            // Snapshot height before collapsing so CSS transition fires correctly
            el.style.overflow  = 'hidden';
            el.style.maxHeight = el.scrollHeight + 'px';
            el.getBoundingClientRect(); // force reflow
            el.style.maxHeight = '0';
            el.dataset.open    = 'false';
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        } else {
            el.style.overflow  = 'hidden';
            el.style.maxHeight = el.scrollHeight + 'px';
            el.dataset.open    = 'true';
            if (chevron) chevron.style.transform = 'rotate(180deg)';
            // Lift height cap after transition so nested accordions can expand
            el.addEventListener('transitionend', function onEnd() {
                if (el.dataset.open === 'true') {
                    el.style.maxHeight = 'none';
                    el.style.overflow  = 'visible';
                }
                el.removeEventListener('transitionend', onEnd);
            });
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && _userOpen) _closeUserMenu();
    });
</script>

@stack('scripts')

</body>
</html>
