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

        :root { --brand-red: #c1121f; --muted-500: #64748b; --icon-gray: #94a3b8; --muted-pink: #fff0f2; }

            #sidebar {
                transition: width 0.28s cubic-bezier(.2,.9,.2,1), box-shadow 0.2s ease;
                overflow: hidden;
                min-height: 0;
                width: 16rem;
                position: relative;
                background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(249,250,251,0.98) 100%);
                padding: 0.4rem 0.6rem;
                box-shadow: 0 10px 30px rgba(15,23,42,0.03);
                border-right: 1px solid rgba(2,6,23,0.04);
                border-radius: 0 0 12px 0;
                backdrop-filter: blur(6px);
        }

            #sidebar-nav {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }

            .sidebar-top {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.35rem 0.55rem;
            margin-bottom: 0.25rem;
        }

            /* Enhanced logo badge: gradient background, subtle glow, hover lift */
            .logo-badge {
                width: 56px;
                height: 56px;
                display: inline-grid;
                place-items: center;
                padding: 6px;
                border-radius: 12px;
                background: linear-gradient(180deg,#ffffff,#fbfdff);
                box-shadow: 0 6px 18px rgba(2,6,23,0.04);
                position: relative;
                overflow: visible;
                transition: transform .18s ease, box-shadow .18s ease;
            }

            .logo-badge::before {
                content: '';
                position: absolute;
                inset: -4px;
                border-radius: 14px;
                background: linear-gradient(135deg, rgba(193,18,31,0.06), rgba(239,68,68,0.02));
                filter: blur(8px);
                z-index: 0;
                transition: opacity .18s ease, transform .18s ease;
            }

            .logo-wrap {
                width: 44px;
                height: 44px;
                border-radius: 10px;
                background: linear-gradient(180deg,#fff,#f8fafc);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
                box-shadow: 0 6px 18px rgba(2,6,23,0.04);
            }

            .logo-img {
                height: 28px;
                width: auto;
                display: block;
                z-index: 3;
                transform: translateZ(0);
            }

            .logo-badge:hover {
                transform: translateY(-4px) scale(1.04);
                box-shadow: 0 20px 44px rgba(2,6,23,0.08);
            }

            .logo-badge:focus { outline: none; box-shadow: 0 0 0 6px rgba(193,18,31,0.06); }

            /* subtle brand accent dot */
            .logo-badge::after {
                content: '';
                position: absolute;
                right: 6px;
                bottom: 6px;
                width: 10px;
                height: 10px;
                border-radius: 9999px;
                background: var(--brand-red);
                box-shadow: 0 4px 12px rgba(193,18,31,0.18);
                z-index: 4;
                border: 2px solid #fff;
            }

        .sidebar-block {
            margin-bottom: 0.15rem;
        }

        .sidebar-head,
        .sidebar-link,
        .sidebar-subtoggle {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.48rem 2.6rem 0.48rem 0.9rem; /* tighter vertical padding for compact look */
            color: #374151;
            background: transparent;
            border-radius: 10px;
            margin: 0.04rem 0;
            position: relative;
            transition: background-color 0.16s ease, color 0.14s ease, transform 0.12s ease, box-shadow 0.16s ease;
        }

        .sidebar-head {
            font-size: 0.90rem; /* slightly reduced further */
            font-weight: 600;
        }

        .sidebar-link,
        .sidebar-subtoggle {
            font-size: 0.80rem; /* reduced main menu size further */
            font-weight: 500;
        }

        .sidebar-head,
        .sidebar-link,
        .sidebar-subtoggle {
            line-height: 1;
        }

        .sidebar-head > span,
        .sidebar-link > span,
        .sidebar-subtoggle > span {
            line-height: 20px;
            display: flex;
            align-items: center;
        }

        .sidebar-head svg,
        .sidebar-link svg,
        .sidebar-subtoggle svg {
            color: inherit;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            display: block;
            transition: color .14s ease, transform .14s ease;
        }

        /* icon kiri: naikkan sedikit supaya sejajar optik dengan teks */
        .sidebar-head > svg:first-child,
        .sidebar-link > svg:first-child,
        .sidebar-subtoggle > svg:first-child {
            transform: translateY(-1px);
        }

        .sidebar-head > svg:last-child,
        .sidebar-link > svg:last-child,
        .sidebar-subtoggle > svg:last-child,
        .sidebar-sublink > svg:last-child {
            position: absolute;
            right: 2.2rem;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 0;
            margin-right: 0;
            pointer-events: none;
            width: 18px;
            height: 18px;
            color: inherit;
            transition: transform .22s cubic-bezier(.2,.9,.2,1), color .14s ease, opacity .14s ease;
            transform-origin: center;
        }

        /* rotated state for chevrons when the accordion is open */
        .chev-rot {
            transform: translateY(-50%) rotate(180deg) !important;
            color: var(--brand-red);
        }

        /* Smooth dropdown animation: height + fade + lift */
        .acc-panel {
            transition: max-height 320ms cubic-bezier(.2,.9,.2,1), opacity 220ms ease, transform 220ms cubic-bezier(.2,.9,.2,1);
            opacity: 0;
            transform: translateY(-6px);
            will-change: max-height, opacity, transform;
        }

        .acc-panel[data-open="true"] {
            opacity: 1;
            transform: translateY(0);
        }

        /* Staggered reveal for links inside accordion */
        .acc-panel .sidebar-sublink,
        .acc-panel .sidebar-link {
            opacity: 0;
            transform: translateY(-6px);
            transition: opacity 220ms ease, transform 220ms cubic-bezier(.2,.9,.2,1);
        }

        .acc-panel[data-open="true"] .sidebar-sublink,
        .acc-panel[data-open="true"] .sidebar-link {
            opacity: 1;
            transform: translateY(0);
        }

        .sidebar-head:hover,
        .sidebar-link:hover,
        .sidebar-subtoggle:hover {
            background: var(--muted-pink);
            color: var(--brand-red);
            transform: translateY(-0.5px);
            box-shadow: 0 8px 20px rgba(2,6,23,0.03);
        }

        /* Active visual with right-edge accent */
        .sidebar-link-active {
            background: var(--muted-pink);
            color: var(--brand-red);
            box-shadow: 0 10px 28px rgba(2,6,23,0.03);
            transform: translateY(-1px);
            transition: background .18s ease, box-shadow .18s ease, color .12s ease, transform .12s ease;
        }

        .sidebar-link-active > svg:first-child,
        .sidebar-head.sidebar-link-active > svg:first-child,
        .sidebar-subtoggle.sidebar-link-active > svg:first-child {
            background: rgba(255,255,255,0.9);
            border-radius: 8px;
            padding: 4px;
            color: var(--brand-red);
            box-shadow: 0 6px 14px rgba(2,6,23,0.04);
            transition: background .12s ease, color .12s ease, transform .12s ease, box-shadow .12s ease;
        }

        .sidebar-link-active::after {
            content: '';
            position: absolute;
            right: 1.2rem; /* leaves space for chevron */
            top: 10px;
            bottom: 10px;
            width: 3px;
            border-radius: 3px;
            background: linear-gradient(180deg, var(--brand-red), #ef4444);
            box-shadow: 0 1px 4px rgba(193,18,31,0.06);
            transition: transform .18s cubic-bezier(.2,.9,.2,1), opacity .12s ease;
        }

        .sidebar-sublink {
            display: flex;
            align-items: center;
            position: relative;
            padding: 0.46rem 2.6rem 0.46rem 2.3rem;
            font-size: 0.74rem; /* slightly smaller than main menu */
            color: #6b7280;
            font-weight: 500;
            border-radius: 8px;
            margin: 0.02rem 0;
            transition: background-color 0.12s ease, color 0.12s ease, transform 0.12s ease;
        }

        .sidebar-block + .sidebar-block {
            border-top: 1px solid rgba(148,163,184,0.16);
            padding-top: 0.2rem;
            margin-top: 0.2rem;
        }

        #sidebar-nav::-webkit-scrollbar { width: 6px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: #e6eef8; border-radius: 9999px; }

        /* Modern toolbar card: softer, compact, aligned controls */
        /* Toggle button (modern): crossfade icons, soft shadow, focus ring */
        #sidebar-toggle-btn {
            --btn-size: 36px;
            width: var(--btn-size);
            height: var(--btn-size);
            padding: 0;
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(255,250,250,0.98));
            border: 1px solid rgba(2,6,23,0.06);
            box-shadow: 0 8px 22px rgba(2,6,23,0.06);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 160ms ease, box-shadow 160ms ease, background 160ms ease;
            cursor: pointer;
            overflow: hidden;
        }

        #sidebar-toggle-btn:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(193,18,31,0.08);
        }

        #sidebar-toggle-btn svg {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 18px;
            height: 18px;
            transition: opacity .18s ease, transform .22s cubic-bezier(.2,.9,.2,1), color .14s ease;
            color: var(--brand-red);
        }

        #sidebar-toggle-btn #icon-open { opacity: 0; transform: translate(-50%,-50%) scale(.92) rotate(-18deg); }
        #sidebar-toggle-btn #icon-close { opacity: 1; transform: translate(-50%,-50%) scale(1) rotate(0deg); }

        #sidebar-toggle-btn.collapsed { background: linear-gradient(180deg,#fff6f7,#fff); }
        #sidebar-toggle-btn.collapsed #icon-open { opacity: 1; transform: translate(-50%,-50%) scale(1) rotate(0deg); }
        #sidebar-toggle-btn.collapsed #icon-close { opacity: 0; transform: translate(-50%,-50%) scale(.86) rotate(18deg); }

        #sidebar-toggle-btn:hover { transform: translateY(-2px) scale(1.04); box-shadow: 0 12px 32px rgba(2,6,23,0.08); }
        .prestasi-filters-top, .proposal-toolbar {
            display: flex;
            gap: 12px;
            align-items: center;
            background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(248,250,252,0.96));
            border: 1px solid rgba(2,6,23,0.04);
            border-radius: 12px;
            padding: 8px 12px;
            box-shadow: 0 8px 24px rgba(15,23,42,0.04);
        }

        /* Per-page select: compact pill with subtle shadow (custom arrow via markup) */
        .proposal-perpage select,
        .prestasi-perpage select,
        #per-page-select,
        .proposal-perpage select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            height: 40px;
            padding: 0 26px 0 12px; /* right padding leaves room for arrow */
            background: #fff;
            border: 1px solid rgba(2,6,23,0.06);
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(15,23,42,0.04);
            color: #0f172a;
            font-weight: 700;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px;
            line-height: 1;
            text-align: left; /* ensure value sits on the left so arrow appears on the right */
        }
        /* Hide native dropdown arrow in IE/Edge and ensure consistent appearance */
        select::-ms-expand { display: none; }
        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }

        /* Search input modern */
        :root {
            --search-accent: #c1121f;
            --search-soft: #fff1f3;
            --search-border: rgba(193, 18, 31, 0.14);
        }

        .search-box {
            position: relative;
            width: 100%;
            max-width: 320px; /* reduced width to make search input shorter */
            margin-left: auto;
        }

        #search-input {
            width: 100%;
            height: 38px;
            padding: 0 40px 0 44px;
            border-radius: 9999px;
            border: 1px solid var(--search-border) !important;
            background: linear-gradient(180deg, #ffffff 0%, var(--search-soft) 100%) !important;
            color: #0f172a;
            font-size: 13px;
            font-weight: 500;
            box-shadow:
                0 10px 24px rgba(15, 23, 42, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transition: all 180ms ease;
            position: relative;
            z-index: 1;
        }

        /* hide native browser clear/cancel icons for input[type=search] to avoid duplicates */
        input[type="search"]::-webkit-search-decoration,
        input[type="search"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-results-decoration,
        input[type="search"]::-webkit-search-results-button {
            -webkit-appearance: none;
            appearance: none;
            display: none;
        }
        input[type="search"]::-ms-clear,
        input[type="search"]::-ms-reveal {
            display: none;
            width: 0;
            height: 0;
        }

        #search-input::placeholder {
            color: #94a3b8 !important;
            font-weight: 400; /* regular */
        }

        #search-input:hover {
            border-color: rgba(193, 18, 31, 0.25) !important;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        }

        #search-input:focus {
            outline: none;
            background: #ffffff !important;
            border-color: rgba(193, 18, 31, 0.45) !important;
            box-shadow:
                0 0 0 4px rgba(193, 18, 31, 0.08),
                0 16px 36px rgba(193, 18, 31, 0.12);
            transform: translateY(-1px);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--search-accent);
            pointer-events: none;
            opacity: 0.9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        .search-clear {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border-radius: 9999px;
            display: none;
            align-items: center;
            justify-content: center;
            border: none;
            background: rgba(193, 18, 31, 0.08);
            color: var(--search-accent);
            cursor: pointer;
            transition: all 160ms ease;
            z-index: 4;
        }

        .search-box.has-value .search-clear {
            display: inline-flex;
        }

        .search-clear:hover {
            background: var(--search-accent);
            color: #ffffff;
            transform: translateY(-50%) scale(1.04);
        }

        /* Radio inputs: use brand red accent where supported */
        input[type="radio"] {
            accent-color: var(--brand-red);
        }

        @media (max-width: 640px) {
            .search-box { max-width: none; }

            #search-input {
                height: 36px;
                padding-left: 38px;
                padding-right: 38px;
                font-size: 12px;
            }
        }

        /* make inline SVG search icon in the container more visible */
        .relative > svg, .relative svg.pointer-events-none {
            color: var(--search-accent) !important;
            stroke: currentColor;
            opacity: 0.95;
        }

        /* Placeholder color for better contrast on pink background */
        #search-input::placeholder,
        .prestasi-search input::placeholder,
        .proposal-search input::placeholder,
        .prestasi-search-right input::placeholder,
        .proposal-right input::placeholder {
            color: rgba(15,23,42,0.48) !important;
        }

        #search-input:focus,
        .prestasi-search input:focus,
        .proposal-search input:focus {
            outline: none;
            box-shadow: 0 18px 48px rgba(244,63,94,0.12);
            transform: translateY(-1px);
        }

        /* Fallback for small screens */
        @media (max-width: 480px) {
            .proposal-perpage select,
            .prestasi-perpage select,
            #per-page-select {
                padding-right: 28px;
                background-position: right 10px center;
                height: 34px;
            }
            .prestasi-search-right input[type='search'], .proposal-right input[type='search'] {
                width: 100%;
                max-width: none;
                padding-left: 36px;
            }
            .prestasi-filters-top, .proposal-toolbar { gap: 8px; padding: 6px; }
        }

        /* ---------- Global card + table refinements ---------- */
        .rounded-2xl.bg-white.shadow-sm.border {
            border-radius: 18px !important;
            box-shadow: 0 18px 48px rgba(15,23,42,0.06) !important;
            border-color: rgba(2,6,23,0.04) !important;
            overflow: visible;
            /* softer card background so not everything appears pure white */
            background: linear-gradient(180deg, #fbfdff 0%, #f3f6f9 100%);
        }

        /* Softer toolbar/header inside cards */
        .rounded-2xl.bg-white.shadow-sm.border > .flex.flex-col,
        .rounded-2xl.bg-white.shadow-sm.border > .flex.flex-col > .flex {
            background: linear-gradient(180deg, #fbfdff 0%, #f8fafc 100%);
        }

        /* Table row card effect: use separate collapse to allow spacing */
        .rounded-2xl.bg-white.shadow-sm.border .overflow-x-auto > table {
            border-collapse: separate !important;
            border-spacing: 0 10px;
        }

        .rounded-2xl.bg-white.shadow-sm.border table thead tr {
            background: transparent !important;
        }

        .rounded-2xl.bg-white.shadow-sm.border table tbody tr {
            background: #ffffff; /* keep rows bright for contrast */
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(2,6,23,0.04);
            border: 1px solid rgba(2,6,23,0.03); /* subtle separation against the soft card */
            transition: transform 0.12s ease, box-shadow 0.12s ease;
        }

        .rounded-2xl.bg-white.shadow-sm.border table tbody tr:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 36px rgba(2,6,23,0.06);
        }

        /* Slightly larger spacing for filter/toolbars */
        .prestasi-filters-top, .proposal-toolbar {
            gap: 18px;
            align-items: center;
        }

        .prestasi-search-right, .proposal-right {
            margin-left: auto;
        }

        /* Softer header */
        header.z-40 {
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            box-shadow: 0 10px 34px rgba(2,6,23,0.03);
            border-bottom: 1px solid rgba(2,6,23,0.04);
        }

        /* ---------- Dark mode overrides ---------- */
        .dark body {
            background: #111827;
            color: #e2e8f0;
        }

        .dark #main-content {
            background: #111827;
        }

        .dark header.z-40 {
            background: linear-gradient(180deg, #0f172a, #0b1220);
            border-bottom: 1px solid rgba(148,163,184,0.2);
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
        }

        .dark #sidebar {
            background: linear-gradient(180deg, #0f172a, #0b1220);
            border-right: 1px solid rgba(148,163,184,0.2);
            box-shadow: none;
        }

        .dark .logo-badge {
            background: linear-gradient(180deg, #0f172a, #0b1220);
            box-shadow: 0 10px 28px rgba(2,6,23,0.55);
        }

        .dark .logo-badge::before {
            background: linear-gradient(135deg, rgba(248,113,113,0.12), rgba(244,63,94,0.04));
        }

        .dark .logo-wrap {
            background: linear-gradient(180deg, #111827, #0f172a);
            box-shadow: 0 8px 18px rgba(2,6,23,0.45);
        }

        .dark .logo-img {
            filter: brightness(1.08) contrast(1.08);
        }

        .dark .logo-badge::after {
            border-color: #0f172a;
            box-shadow: 0 6px 16px rgba(248,113,113,0.22);
        }

        .dark .sidebar-head,
        .dark .sidebar-link,
        .dark .sidebar-subtoggle,
        .dark .sidebar-sublink,
        .dark .sidebar-user .name,
        .dark .sidebar-user .role {
            color: #e2e8f0;
        }

        .dark .sidebar-head:hover,
        .dark .sidebar-link:hover,
        .dark .sidebar-subtoggle:hover {
            background: rgba(148,163,184,0.12);
            color: #f8fafc;
        }

        .dark .sidebar-link-active,
        .dark .sidebar-head.sidebar-link-active,
        .dark .sidebar-subtoggle.sidebar-link-active {
            background: rgba(148,163,184,0.16);
            color: #f8fafc;
            box-shadow: none;
        }

        .dark .sidebar-link-active > svg:first-child,
        .dark .sidebar-head.sidebar-link-active > svg:first-child,
        .dark .sidebar-subtoggle.sidebar-link-active > svg:first-child {
            background: rgba(148,163,184,0.18);
            color: #f8fafc;
            box-shadow: none;
        }

        .dark .rounded-2xl.bg-white {
            background: #111827 !important;
            border-color: rgba(148,163,184,0.2) !important;
            box-shadow: 0 18px 40px rgba(2,6,23,0.55), 0 0 0 1px rgba(255,255,255,0.04) !important;
        }

        .dark .bg-white {
            background: #0f172a !important;
        }

        .dark .rounded-2xl.bg-white.shadow-sm.border {
            background: #0b1220 !important;
            box-shadow: 0 20px 44px rgba(2,6,23,0.6), 0 0 0 1px rgba(255,255,255,0.04) !important;
        }

        .dark .shadow-lg {
            box-shadow: 0 22px 48px rgba(2,6,23,0.6), 0 0 0 1px rgba(255,255,255,0.04) !important;
        }

        .dark .shadow-sm,
        .dark .shadow {
            box-shadow: 0 14px 32px rgba(2,6,23,0.5), 0 0 0 1px rgba(255,255,255,0.03) !important;
        }

        .dark .rounded-2xl.bg-white.shadow-sm.border table thead,
        .dark .rounded-2xl.bg-white.shadow-sm.border .bg-gray-50 {
            background: #111827 !important;
        }

        .dark .rounded-2xl.bg-white.shadow-sm.border table tbody tr {
            background: #0f172a;
            border-color: rgba(148,163,184,0.15);
            box-shadow: none;
        }

        .dark .text-gray-900,
        .dark .text-gray-800,
        .dark .text-gray-700,
        .dark .text-gray-600,
        .dark .text-gray-500 {
            color: #e2e8f0 !important;
        }

        .dark .text-gray-400 {
            color: #cbd5f5 !important;
        }

        .dark .text-gray-300 {
            color: #aab4cf !important;
        }

        .dark .border-gray-100,
        .dark .border-gray-200 {
            border-color: rgba(148,163,184,0.2) !important;
        }

        .dark .border-gray-400 {
            border-color: rgba(148,163,184,0.45) !important;
        }

        .dark .bg-gray-50,
        .dark .bg-slate-50 {
            background: #111827 !important;
        }

        .dark .bg-gray-200,
        .dark .bg-slate-200 {
            background: #1f2937 !important;
        }

        .dark .bg-gray-100 {
            background: #111827 !important;
        }

        .dark #pagination-wrapper {
            background: #0f172a !important;
            border-top-color: rgba(148,163,184,0.2) !important;
        }

        .dark #pagination-buttons button {
            background: #111827 !important;
            color: #e2e8f0;
            border-color: #111827 !important;
            box-shadow: none !important;
        }

        .dark #pagination-buttons button:hover {
            background: #1f2937 !important;
        }

        .dark #pagination-buttons button:disabled {
            background: #0b1220;
            color: #94a3b8;
            opacity: 1 !important;
        }

        .dark #pagination-buttons button.bg-red-600 {
            background: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #ffffff;
        }

        .dark #pagination-buttons button.bg-red-600:hover {
            background: #b91c1c !important;
        }

        .dark #pagination-buttons span {
            color: #cbd5f5 !important;
        }

        .dark .bg-green-50 { background: rgba(34,197,94,0.16) !important; }
        .dark .bg-blue-50 { background: rgba(59,130,246,0.16) !important; }
        .dark .bg-blue-100 { background: rgba(59,130,246,0.22) !important; }
        .dark .bg-yellow-50 { background: rgba(234,179,8,0.18) !important; }
        .dark .bg-red-50 { background: rgba(239,68,68,0.16) !important; }
        .dark .bg-purple-100 { background: rgba(168,85,247,0.2) !important; }
        .dark .bg-\[\#fcfcfc\] { background: #0b1220 !important; }

        .dark .hover\:bg-gray-50:hover {
            background: #111827 !important;
        }

        .dark input,
        .dark textarea,
        .dark select {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
            box-shadow: none !important;
        }

        .dark input[type="radio"] {
            background-color: #0f172a !important;
            border-color: rgba(148,163,184,0.6) !important;
            accent-color: #ef4444;
            box-shadow: none !important;
        }

        .dark input[type="radio"]:not(:checked) {
            background-color: #0b1220 !important;
        }

        .dark input[type="date"] {
            color-scheme: dark;
        }

        .dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.9;
        }

        .dark #search-input {
            background: #0f172a !important;
            border-color: rgba(148,163,184,0.35) !important;
            color: #e2e8f0 !important;
            box-shadow: none !important;
        }

        .dark #search-input::placeholder {
            color: rgba(226,232,240,0.6) !important;
        }

        .dark #user-btn {
            background: rgba(148,163,184,0.12);
        }

        .dark #per-page-select,
        .dark select {
            background-color: #0f172a !important;
            border-color: rgba(148,163,184,0.35) !important;
            color: #e2e8f0 !important;
            box-shadow: none !important;
        }

        .dark #user-dropdown {
            background: #0f172a;
            border-color: rgba(148,163,184,0.2);
        }

        .dark footer {
            background: #0b1220;
            border-top: 1px solid rgba(148,163,184,0.2);
        }
    </style>
</head>

<body class="bg-slate-100 antialiased">

<div class="flex h-screen flex-col overflow-hidden">

    <div class="flex flex-1 overflow-hidden">

        @php
            $currentUser = auth()->user();
            $userRole = $currentUser?->role ?? 'Kemahasiswaan';
            $canUseAdminSidebar = $currentUser?->isAdmin() || $currentUser?->isSuperAdmin() || $currentUser?->isDpmbem() || $currentUser?->isOrmawa();
            $isOrganisasiActive = request()->routeIs('admin.beranda_ormawa');
            $isPrestasiOrmawaActive = request()->routeIs('admin.prestasi_ormawa');
            $isPrestasiActive = request()->routeIs('admin.prestasi_mahasiswa');
            $isPrestasiPanelActive = $isPrestasiOrmawaActive || $isPrestasiActive;
            $isTemplateActive = request()->routeIs('admin.template_proposal');
            $isKontrolActive = request()->routeIs('admin.kontrol_akun');
            $isMonitoringAnggaran = request()->routeIs('admin.monitoring_anggaran');
        @endphp

        <aside id="sidebar" class="flex-shrink-0 flex flex-col shadow-md z-30">
            <nav id="sidebar-nav" class="flex-1 overflow-y-auto overflow-x-hidden py-2" style="min-width: 16rem;">

                <div class="sidebar-top">
                    <a href="{{ url('/') }}" class="logo-badge mb-3" aria-label="TOP home">
                        <div class="logo-wrap">
                            <img src="{{ asset('top_logo.png') }}" alt="TOP" class="logo-img" />
                        </div>
                    </a>
                </div>

                @if ($canUseAdminSidebar)
                <div class="sidebar-block">
                    <a href="{{ route('admin.beranda_ormawa') }}" class="sidebar-head {{ $isOrganisasiActive ? 'sidebar-link-active' : '' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Organisasi Mahasiswa</span>
                    </a>
                </div>

                <div class="sidebar-block">
                    <a href="{{ route('admin.users.index') }}" class="sidebar-head {{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : '' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Manajemen User</span>
                    </a>
                </div>

                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <a href="{{ route('admin.atur_deadline') }}" class="sidebar-head {{ request()->routeIs('admin.atur_deadline') ? 'sidebar-link-active' : '' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Atur Deadline</span>
                    </a>
                </div>

                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <a href="{{ route('admin.verifikasi_publikasi') }}" class="sidebar-head {{ request()->routeIs('admin.verifikasi_publikasi') ? 'sidebar-link-active' : '' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Verifikasi Publikasi</span>
                    </a>
                </div>

                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <button
                        onclick="toggleAcc('acc-prestasi')"
                        class="sidebar-head {{ $isPrestasiPanelActive ? 'text-red-600' : '' }}"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Prestasi Mahasiswa</span>
                        <svg id="chevron-acc-prestasi" class="h-4 w-4 flex-shrink-0 transition-transform duration-200 {{ $isPrestasiPanelActive ? 'chev-rot' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="acc-prestasi" class="acc-panel" style="max-height: {{ $isPrestasiPanelActive ? '1000px' : '0' }}; overflow: hidden;" data-open="{{ $isPrestasiPanelActive ? 'true' : 'false' }}">
                        <a href="{{ route('admin.prestasi_ormawa') }}" class="sidebar-sublink {{ $isPrestasiOrmawaActive ? 'text-red-600 font-semibold' : '' }}">
                            Prestasi Ormawa
                        </a>
                        <a href="{{ route('admin.prestasi_mahasiswa') }}" class="sidebar-sublink {{ $isPrestasiActive ? 'text-red-600 font-semibold' : '' }}">
                            Prestasi Mahasiswa
                        </a>
                    </div>
                </div>
                @endif

                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <a href="{{ route('admin.template_proposal') }}" class="sidebar-head {{ $isTemplateActive ? 'sidebar-link-active' : '' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Template Dokumen</span>
                    </a>
                </div>

                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <a href="{{ route('admin.kontrol_akun') }}" class="sidebar-head {{ $isKontrolActive ? 'sidebar-link-active' : '' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Kontrol Akun</span>
                    </a>
                </div>


                @if($currentUser?->isDpmbem() || $currentUser?->isAdmin() || $currentUser?->isSuperAdmin() || $userRole === 'Kemahasiswaan')
                <div class="sidebar-block mt-2 border-t border-slate-300 pt-2">
                    <a href="{{ route('admin.monitoring_anggaran') }}" class="sidebar-link {{ $isMonitoringAnggaran ? 'sidebar-link-active' : '' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="flex-1 whitespace-nowrap text-left">Monitoring Anggaran</span>
                    </a>
                </div>
                @endif
            </nav>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-hidden">

            @include('partials.shared_navbar')

            <button
                id="sidebar-toggle-btn"
                onclick="toggleSidebar()"
                class="absolute z-50 flex h-8 w-8 items-center justify-center rounded-full bg-white text-red-600 border border-red-100 shadow-sm hover:shadow-md transition-all"
                aria-label="Sembunyikan / Tampilkan sidebar"
                style="left:calc(16rem - 8px); top:0.8rem;"
            >
                <svg id="icon-close" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <svg id="icon-open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <main class="flex-1 overflow-y-auto overflow-x-hidden px-5 pb-5 pt-12 sm:px-6 sm:pt-12" id="main-content">

                @if (session('success'))
                <div id="flash-success"
                     class="mb-4 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm transition-all duration-500">
                    <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button type="button" onclick="this.closest('#flash-success').remove()" class="text-green-500 hover:text-green-700 transition-colors" aria-label="Tutup">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <script>
                    setTimeout(() => {
                        const el = document.getElementById('flash-success');
                        if (el) {
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 500);
                        }
                    }, 5000);
                </script>
                @endif

                @if (session('error'))
                <div id="flash-error"
                     class="mb-4 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm transition-all duration-500">
                    <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button type="button" onclick="this.closest('#flash-error').remove()" class="text-red-500 hover:text-red-700 transition-colors" aria-label="Tutup">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <script>
                    setTimeout(() => {
                        const el = document.getElementById('flash-error');
                        if (el) {
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 500);
                        }
                    }, 7000);
                </script>
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

    function positionToggle() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        if (!sidebar || !toggleBtn) return;
        const sidebarRect = sidebar.getBoundingClientRect();
        const parentRect = toggleBtn.parentElement.getBoundingClientRect();
        // nudge left by 8px so the button isn't floating with too much left-gap
        const x = sidebarRect.right - parentRect.left - (toggleBtn.offsetWidth / 2) - 8;
        // keep some minimum padding
        toggleBtn.style.left = Math.max(6, Math.round(x)) + 'px';
    }

    function toggleSidebar() {
        _sidebarOpen = !_sidebarOpen;
        const sidebar   = document.getElementById('sidebar');
        const iconClose = document.getElementById('icon-close');
        const iconOpen  = document.getElementById('icon-open');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');

        if (_sidebarOpen) {
            sidebar.style.width = '16rem';
            toggleBtn.classList.remove('collapsed');
            toggleBtn.setAttribute('aria-expanded', 'true');
        } else {
            sidebar.style.width = '0';
            toggleBtn.classList.add('collapsed');
            toggleBtn.setAttribute('aria-expanded', 'false');
        }

        // position the button after layout change
        setTimeout(positionToggle, 70);
    }

    // Ensure the toggle button aligns to the sidebar seam on initial load and resize
    document.addEventListener('DOMContentLoaded', function () {
        positionToggle();
    });
    window.addEventListener('resize', positionToggle);

    // Initial highlight for top sidebar item after login; cleared on first interaction
    (function () {
        const KEY = 'sidebarInteracted';
        document.addEventListener('DOMContentLoaded', function () {
            const nav = document.getElementById('sidebar-nav');
            if (!nav) return;
            if (!sessionStorage.getItem(KEY)) {
                const firstHead = nav.querySelector('.sidebar-head') || nav.querySelector('.sidebar-link');
                if (firstHead) {
                    firstHead.classList.add('sidebar-link-active');
                    firstHead.setAttribute('data-initial-highlight', 'true');
                }
            }

            // Single-active behavior + clear initial highlight on first interaction
            nav.addEventListener('click', function (e) {
                if (!sessionStorage.getItem(KEY)) {
                    sessionStorage.setItem(KEY, '1');
                    const inits = nav.querySelectorAll('[data-initial-highlight="true"]');
                    inits.forEach(el => {
                        el.classList.remove('sidebar-link-active');
                        el.removeAttribute('data-initial-highlight');
                    });
                }

                // Keep only one visual active item at a time
                const clicked = e.target.closest('.sidebar-head, .sidebar-link, .sidebar-subtoggle, .sidebar-sublink');
                if (!clicked) return;
                const prev = nav.querySelectorAll('.sidebar-link-active');
                prev.forEach(p => { if (p !== clicked) p.classList.remove('sidebar-link-active'); });
                clicked.classList.add('sidebar-link-active');
            });
        });
    })();

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
            if (chevron) chevron.classList.remove('chev-rot');
        } else {
            el.style.overflow  = 'hidden';
            el.style.maxHeight = el.scrollHeight + 'px';
            el.dataset.open    = 'true';
            if (chevron) chevron.classList.add('chev-rot');
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

    // Search box: clear button + input handling (modern behavior)
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.search-box').forEach(function(box) {
            const input = box.querySelector('input[type="search"], input[type="text"], #search-input');
            const clear = box.querySelector('.search-clear');
            if (!input) return;

            const update = function() {
                const has = input.value.trim().length > 0;
                box.classList.toggle('has-value', has);
                if (clear) clear.style.display = has ? 'inline-flex' : 'none';
            };

            // sync with existing on-page filter function
            input.addEventListener('input', function () {
                update();
                if (typeof filterTable === 'function') filterTable();
            });

            if (clear) {
                clear.addEventListener('click', function (e) {
                    e.preventDefault();
                    input.value = '';
                    update();
                    input.focus();
                    if (typeof filterTable === 'function') filterTable();
                });
            }

            update();
        });
    });
</script>

@stack('scripts')

</body>
</html>
