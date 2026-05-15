@php
    $currentUser = auth()->user();
    $userLabel = trim(($currentUser?->nama_depan ?? '') . ' ' . ($currentUser?->nama_belakang ?? ''));
    $userLabel = $userLabel !== '' ? $userLabel : ($currentUser?->username ?? 'Pengguna');
@endphp

<style>
    .logout-hover-pink:hover {
        background: #f6e9ec;
        color: #be123c;
    }

    /* Softer header to match app card visual language */
    header.z-40 {
        background: linear-gradient(180deg, #ffffff, #fbfdff);
        box-shadow: 0 10px 30px rgba(2,6,23,0.03);
        border-bottom: 1px solid rgba(2,6,23,0.04);
    }

    header.z-40 a img {
        border-radius: 10px;
        padding: 6px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(2,6,23,0.04);
    }

    #user-btn {
        background: rgba(0,0,0,0.02);
        padding: 7px 10px;
        border-radius: 10px;
    }

    #user-dropdown {
        border-radius: 12px;
        box-shadow: 0 18px 40px rgba(2,6,23,0.06);
    }

    .theme-toggle {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(0,0,0,0.02);
        color: #64748b;
        transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
    }

    .theme-toggle:hover {
        background: #f1f5f9;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .theme-toggle:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(193,18,31,0.08);
    }

    .theme-toggle svg {
        width: 18px;
        height: 18px;
    }

    .theme-toggle .icon-sun {
        display: none;
    }

    .dark .theme-toggle .icon-sun {
        display: inline-flex;
    }

    .dark .theme-toggle .icon-moon {
        display: none;
    }
</style>

<header class="z-40 flex h-16 flex-shrink-0 items-center justify-end bg-white px-6 shadow-sm">
    <div class="flex items-center gap-2">
        <button
            id="theme-toggle"
            type="button"
            class="theme-toggle"
            aria-label="Toggle dark mode"
            title="Dark mode"
        >
            <svg class="icon-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
            </svg>
            <svg class="icon-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414M16.95 16.95l1.414 1.414M7.05 7.05L5.636 5.636M12 8a4 4 0 100 8 4 4 0 000-8z" />
            </svg>
        </button>

        <div class="relative" id="user-wrapper">
            <button
                id="user-btn"
                onclick="toggleUserMenu()"
                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
                aria-haspopup="true"
                aria-expanded="false"
            >
                {{ $userLabel }}
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
                <form method="POST" action="{{ url('/logout') }}" class="px-2 py-1">
                    @csrf
                    <button
                        type="submit"
                        class="logout-hover-pink flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-red-600 transition-colors duration-150"
                        role="menuitem"
                    >
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    (function () {
        const root = document.documentElement;
        const key = 'theme';
        const btn = document.getElementById('theme-toggle');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const stored = localStorage.getItem(key);
        const initial = stored || (prefersDark ? 'dark' : 'light');

        if (initial === 'dark') {
            root.classList.add('dark');
        } else {
            root.classList.remove('dark');
        }

        if (btn) {
            btn.addEventListener('click', function () {
                const next = root.classList.contains('dark') ? 'light' : 'dark';
                if (next === 'dark') {
                    root.classList.add('dark');
                } else {
                    root.classList.remove('dark');
                }
                localStorage.setItem(key, next);
            });
        }
    })();
</script>
