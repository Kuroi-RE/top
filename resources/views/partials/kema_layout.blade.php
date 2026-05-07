<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TOP') }} &mdash; @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
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

    @include('partials.shared_navbar')

    <div class="flex flex-1 overflow-hidden">

        @php
            $userRole = session('dummy_user.role', 'kemahasiswaan');
            $isOrganisasiActive = request()->routeIs('admin.beranda_ormawa');
            $isPrestasiOrmawaActive = request()->routeIs('admin.prestasi_ormawa');
            $isPrestasiActive = request()->routeIs('admin.prestasi_mahasiswa');
            $isTemplateActive = request()->routeIs('admin.template_proposal');
            $isKontrolActive = request()->routeIs('admin.kontrol_akun');
        @endphp

        <aside id="sidebar" class="flex-shrink-0 bg-white flex flex-col shadow-md z-30">
            <nav id="sidebar-nav" class="flex-1 overflow-y-auto overflow-x-hidden py-2" style="min-width: 16rem;">

                @if (in_array($userRole, ['admin', 'kemahasiswaan', 'ketua_institusi', 'ketua_prodi', 'mahasiswa']))
                <a
                    href="{{ route('admin.beranda_ormawa') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-colors {{ $isOrganisasiActive ? 'bg-red-600 text-white hover:bg-red-700' : 'text-gray-700 hover:bg-slate-300' }}"
                >
                    <span class="material-symbols-outlined {{ $isOrganisasiActive ? 'text-white' : 'text-gray-500' }}" style="font-size: 20px;">groups</span>
                    <span class="flex-1 whitespace-nowrap text-left">Organisasi Mahasiswa</span>
                </a>

                <div>
                    <button
                        onclick="toggleAcc('acc-prestasi')"
                        class="flex w-full items-center gap-3 px-4 py-3 text-sm font-semibold transition-colors {{ $isPrestasiOrmawaActive || $isPrestasiActive ? 'bg-red-600 text-white hover:bg-red-700' : 'text-gray-700 hover:bg-slate-300' }}"
                    >
                        <span class="material-symbols-outlined {{ $isPrestasiOrmawaActive || $isPrestasiActive ? 'text-white' : 'text-gray-500' }}" style="font-size: 20px;">verified</span>
                        <span class="flex-1 whitespace-nowrap text-left">Prestasi Mahasiswa</span>
                        <span id="chevron-acc-prestasi" class="material-symbols-outlined transition-transform duration-200" style="font-size: 18px;">expand_more</span>
                    </button>

                    <div id="acc-prestasi" class="acc-panel" style="max-height: 0;" data-open="false">
                        <a href="{{ route('admin.prestasi_ormawa') }}"
                           class="flex items-center whitespace-nowrap bg-gray-700 px-8 py-2.5 text-sm text-white hover:bg-gray-800 transition-colors {{ $isPrestasiOrmawaActive ? 'bg-gray-800' : '' }}">
                            Prestasi Ormawa
                        </a>
                        <a href="{{ route('admin.prestasi_mahasiswa') }}"
                           class="flex items-center whitespace-nowrap bg-gray-700 px-8 py-2.5 text-sm text-white hover:bg-gray-800 transition-colors {{ $isPrestasiActive ? 'bg-gray-800' : '' }}">
                            Prestasi Mahasiswa
                        </a>
                    </div>
                </div>
                @endif

                <a
                    href="{{ route('admin.template_proposal') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-colors {{ $isTemplateActive ? 'bg-red-600 text-white hover:bg-red-700' : 'text-gray-700 hover:bg-slate-300' }}"
                >
                    <span class="material-symbols-outlined {{ $isTemplateActive ? 'text-white' : 'text-gray-500' }}" style="font-size: 20px;">description</span>
                    <span class="flex-1 whitespace-nowrap text-left">Template Dokumen</span>
                </a>

                <a
                    href="{{ route('admin.kontrol_akun') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-colors {{ $isKontrolActive ? 'bg-red-600 text-white hover:bg-red-700' : 'text-gray-700 hover:bg-slate-300' }}"
                >
                    <span class="material-symbols-outlined {{ $isKontrolActive ? 'text-white' : 'text-gray-500' }}" style="font-size: 20px;">manage_accounts</span>
                    <span class="flex-1 whitespace-nowrap text-left">Kontrol Akun</span>
                </a>

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

