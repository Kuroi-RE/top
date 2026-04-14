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
            background: #ffffff;
        }

        .acc-panel {
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sidebar-block {
            margin-bottom: 0;
            overflow: hidden;
            border-radius: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
        }

        .sidebar-head {
            display: flex;
            width: 100%;
            align-items: center;
            gap: 0.75rem;
            background: #dc2626;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            transition: background-color 0.2s ease;
        }

        .sidebar-head:hover {
            background: #b91c1c;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            white-space: nowrap;
            padding: 0.75rem 1.15rem 0.75rem 2.15rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #f8fafc;
            background: #334155;
            transition: background-color 0.2s ease;
        }

        .sidebar-link:hover {
            background: #1f2937;
        }

        .sidebar-link-active {
            background: #1f2937;
        }

        .sidebar-subtoggle {
            display: flex;
            width: 100%;
            align-items: center;
            gap: 0.75rem;
            padding: 0.72rem 1.15rem 0.72rem 2.15rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #dbeafe;
            background: #334155;
            transition: background-color 0.2s ease;
        }

        .sidebar-subtoggle:hover {
            background: #1f2937;
        }

        .sidebar-sublink {
            display: flex;
            align-items: center;
            white-space: nowrap;
            padding: 0.58rem 1rem 0.58rem 3.05rem;
            font-size: 0.84rem;
            color: #d1d5db;
            border-left: 2px solid #475569;
            background: #1f2937;
            transition: all 0.2s ease;
        }

        .sidebar-sublink:hover {
            background: #111827;
            color: #f8fafc;
        }

        .sidebar-sublink-active {
            background: #111827;
            color: #fff;
            border-left-color: #ef4444;
        }

        #sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 9999px; }
    </style>
</head>

<body class="bg-slate-100 antialiased">

<div class="flex h-screen flex-col overflow-hidden">

    @include('partials.shared_navbar')

    <div class="flex flex-1 overflow-hidden">

        @php
            $userRole = session('dummy_user.role', 'ormawa');
        @endphp

        <aside id="sidebar" class="flex-shrink-0 flex flex-col shadow-md z-30">
            <nav id="sidebar-nav" class="flex-1 overflow-y-auto overflow-x-hidden py-2" style="min-width: 16rem;">
                <div class="sidebar-block">
                    <button
                        onclick="toggleAcc('acc-ormawa-institusi')"
                        class="sidebar-head"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Ormawa Institusi</span>
                        <svg id="chevron-acc-ormawa-institusi" class="h-4 w-4 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="acc-ormawa-institusi" class="acc-panel" style="max-height: 0; overflow: hidden;" data-open="false">
                        <a href="{{ route('organisasi.index') }}" class="sidebar-link {{ Request::is('organisasi') || Request::is('organisasi/') ? 'sidebar-link-active' : '' }}">Dashboard</a>
                        <div>
                            <button onclick="toggleAcc('acc-kelola-kegiatan')" class="sidebar-subtoggle">
                                <span class="flex-1 whitespace-nowrap text-left">Kelola Kegiatan</span>
                                <svg id="chevron-acc-kelola-kegiatan" class="h-3.5 w-3.5 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="acc-kelola-kegiatan" class="acc-panel" style="max-height: 0; overflow: hidden;" data-open="false">
                                <a href="{{ route('organisasi.create') }}" class="sidebar-sublink {{ Request::is('organisasi/create') ? 'sidebar-sublink-active' : '' }}">Proposal Kegiatan</a>
                                <a href="{{ route('organisasi.create_lpj') }}" class="sidebar-sublink {{ Request::is('organisasi/create_lpj') ? 'sidebar-sublink-active' : '' }}">Laporan Kegiatan (LPJ)</a>
                                <a href="{{ route('organisasi.publikasi') }}" class="sidebar-sublink {{ Request::is('organisasi/publikasi') ? 'sidebar-sublink-active' : '' }}">Publikasi Kegiatan</a>
                            </div>
                        </div>
                        <a href="{{ route('organisasi.template_dokumen') }}" class="sidebar-link {{ Request::is('organisasi/template-dokumen') ? 'sidebar-link-active' : '' }}">Template Dokumen</a>
                    </div>
                </div>

                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <button
                        onclick="toggleAcc('acc-ormawa-prodi')"
                        class="sidebar-head"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Ormawa Prodi</span>
                        <svg id="chevron-acc-ormawa-prodi" class="h-4 w-4 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="acc-ormawa-prodi" class="acc-panel" style="max-height: 0; overflow: hidden;" data-open="false">
                        <a href="{{ route('organisasi.index') }}" class="sidebar-link {{ Request::is('organisasi') || Request::is('organisasi/') ? 'sidebar-link-active' : '' }}">Dashboard</a>
                        <div>
                            <button onclick="toggleAcc('acc-kelola-kegiatan-prodi')" class="sidebar-subtoggle">
                                <span class="flex-1 whitespace-nowrap text-left">Kelola Kegiatan</span>
                                <svg id="chevron-acc-kelola-kegiatan-prodi" class="h-3.5 w-3.5 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="acc-kelola-kegiatan-prodi" class="acc-panel" style="max-height: 0; overflow: hidden;" data-open="false">
                                <a href="{{ route('organisasi.create') }}" class="sidebar-sublink {{ Request::is('organisasi/create') ? 'sidebar-sublink-active' : '' }}">Proposal Kegiatan</a>
                                <a href="{{ route('organisasi.create_lpj') }}" class="sidebar-sublink {{ Request::is('organisasi/create_lpj') ? 'sidebar-sublink-active' : '' }}">Laporan Kegiatan (LPJ)</a>
                                <a href="{{ route('organisasi.publikasi') }}" class="sidebar-sublink {{ Request::is('organisasi/publikasi') ? 'sidebar-sublink-active' : '' }}">Publikasi Kegiatan</a>
                            </div>
                        </div>
                        <a href="{{ route('organisasi.template_dokumen') }}" class="sidebar-link {{ Request::is('organisasi/template-dokumen') ? 'sidebar-link-active' : '' }}">Template Dokumen</a>
                    </div>
                </div>

                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <button
                        onclick="toggleAcc('acc-prestasi-mahasiswa')"
                        class="sidebar-head"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Prestasi Mahasiswa</span>
                        <svg id="chevron-acc-prestasi-mahasiswa" class="h-4 w-4 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="acc-prestasi-mahasiswa" class="acc-panel" style="max-height: 0; overflow: hidden;" data-open="false">
                        <a href="{{ route('prestasi.index') }}" class="sidebar-link {{ Request::is('prestasi') || Request::is('prestasi/') ? 'sidebar-link-active' : '' }}">
                            Beranda
                        </a>

                        <div>
                            <button onclick="toggleAcc('acc-kelola-prestasi')" class="sidebar-subtoggle">
                                <span class="flex-1 whitespace-nowrap text-left">Kelola Prestasi</span>
                                <svg id="chevron-acc-kelola-prestasi" class="h-3.5 w-3.5 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="acc-kelola-prestasi" class="acc-panel" style="max-height: 0; overflow: hidden;" data-open="false">
                                <a href="{{ route('prestasi.input_proposal') }}" class="sidebar-sublink {{ Request::is('prestasi/input-proposal') ? 'sidebar-sublink-active' : '' }}">
                                    Proposal Kegiatan
                                </a>
                                <a href="{{ route('prestasi.upload_lpj') }}" class="sidebar-sublink {{ Request::is('prestasi/upload-lpj') ? 'sidebar-sublink-active' : '' }}">
                                    Laporan Kegiatan (LPJ)
                                </a>
                                <a href="{{ route('prestasi.laporan_prestasi.biodata') }}" class="sidebar-sublink {{ Request::is('prestasi/laporan-prestasi/*') ? 'sidebar-sublink-active' : '' }}">
                                    Laporan Prestasi
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('prestasi.template_dokumen') }}" class="sidebar-link {{ Request::is('prestasi/template-dokumen') ? 'sidebar-link-active' : '' }}">
                            Template Dokumen
                        </a>
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
