@php
    $sessionUser = session('dummy_user', []);
    $userLabel = $sessionUser['username'] ?? null;

    if (!$userLabel && auth()->check()) {
        $userLabel = auth()->user()->name;
    }

    $userLabel = $userLabel ?: 'Pengguna';
@endphp

<style>
    .logout-hover-pink:hover {
        background: #f6e9ec;
        color: #be123c;
    }
</style>

<header class="z-40 flex h-16 flex-shrink-0 items-center justify-between bg-white px-6 shadow-sm">
    <a href="{{ url('/') }}" class="flex select-none items-center">
        <img src="{{ asset('top_logo.png') }}" alt="TOP" class="h-10 w-auto object-contain" />
    </a>

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
</header>
