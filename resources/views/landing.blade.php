<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HOME &mdash; TOP Telkom Ormawa & Prestasi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideOutLeft {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(-100px); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .carousel-slide { animation: slideInRight 0.6s ease-out; }
        .carousel-slide.exit { animation: slideOutLeft 0.6s ease-out; }
        .carousel-container { position: relative; overflow: hidden; height: 340px; border-radius: 16px; }
        .carousel-slide { position: absolute; width: 100%; height: 100%; top: 0; left: 0; }
        .carousel-slide > div {
            height: 100%;
            padding: 1.75rem 3.75rem;
            border-radius: 16px;
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        .carousel-slide > div > .flex-1:first-child { flex: 1.2; }
        .carousel-slide > div > .placeholder-box {
            flex: 0 0 38%;
            min-height: 170px;
            width: auto;
        }
        .carousel-dots { display: flex; justify-content: center; gap: 8px; margin-top: 16px; }
        .dot { width: 10px; height: 10px; border-radius: 50%; background-color: #d1d5db; cursor: pointer; transition: all 0.3s ease; }
        .dot.active { background-color: #dc2626; width: 24px; border-radius: 5px; }
        .carousel-nav-btn { position: absolute; top: 50%; transform: translateY(-50%); background-color: rgba(255, 255, 255, 0.9); border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; z-index: 10; }
        .carousel-nav-btn:hover { background-color: rgba(255, 255, 255, 1); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
        .carousel-nav-btn.prev { left: 10px; }
        .carousel-nav-btn.next { right: 10px; }

        @media (max-width: 768px) {
            .carousel-container { height: 460px; }
            .carousel-slide > div {
                padding: 1.25rem 1.25rem 2.75rem;
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
            }
            .carousel-slide > div > .placeholder-box {
                flex: none;
                width: 100%;
                min-height: 140px;
            }
            .carousel-nav-btn { top: auto; bottom: 12px; transform: none; }
        }

        .placeholder-box { background-color: #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #6b7280; font-weight: 500; }
        .fade-in { animation: fadeInUp 0.6s ease-out; }

        .events-grid > div {
            --card-lift: 0px;
            --card-scale: 1;
            cursor: pointer;
            transform-origin: center;
            transform: translateY(var(--card-lift)) scale(var(--card-scale));
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
            transition: transform 0.28s ease, box-shadow 0.28s ease;
        }

        .events-grid > div:hover {
            --card-lift: -10px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        }

        .events-grid > div.is-expanded {
            --card-scale: 1.04;
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.18);
            z-index: 20;
        }

        .news-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
            z-index: 60;
            padding: 1rem;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .news-modal-backdrop.is-open {
            display: flex;
        }

        .news-modal-panel {
            width: min(460px, 100%);
            max-height: 88vh;
            overflow-y: auto;
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 28px 72px rgba(2, 6, 23, 0.32);
            animation: fadeInUp 0.25s ease-out;
        }

        .news-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
        }

        .news-modal-close {
            border: none;
            background: transparent;
            cursor: pointer;
            color: #64748b;
            font-size: 1.5rem;
            line-height: 1;
        }

        .news-modal-close:hover {
            color: #0f172a;
        }

        .news-modal-body {
            padding: 0.9rem 1rem 1rem;
        }

        .news-modal-content {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .news-modal-text-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .news-modal-category {
            margin: 0;
            line-height: 1.2;
        }

        .news-modal-title {
            margin: 0;
            line-height: 1.2;
        }

        .news-modal-organizer {
            margin: 0.05rem 0 0;
            line-height: 1.3;
        }

        .news-modal-meta {
            margin: 0.1rem 0 0;
            line-height: 1.35;
        }

        .news-modal-description {
            margin: 0.15rem 0 0;
            line-height: 1.6;
        }

        @media (max-width: 640px) {
            .news-modal-panel {
                width: min(94vw, 100%);
            }

            .news-modal-body {
                padding: 0.85rem;
            }
        }
    </style>
</head>

<body class="bg-white">

    <nav class="border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('top_logo.png') }}" alt="TOP" class="h-10 w-auto object-contain" />
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="rounded-full bg-red-700 px-6 py-2 text-sm font-medium text-white transition hover:bg-red-800">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="bg-gradient-to-b from-yellow-50 to-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="carousel-container mb-8">
                <!-- Default Slides -->
                <div class="carousel-slide" data-slide="0">
                    <div class="flex h-full gap-6 bg-gradient-to-r from-yellow-400 to-yellow-200 rounded-lg p-8 items-center">
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold text-red-700 mb-3">TRIAL CLASS GRATIS!!</h1>
                            <p class="text-lg text-gray-800 mb-3 font-semibold">PERIODE MARET - APRIL 2026</p>
                            <p class="text-gray-700 mb-4">Ikuti kelas percobaan gratis dan daftarkan diri Anda sekarang. Dapatkan sertifikat dan hadiah menarik!</p>
                            <button class="rounded-full bg-red-700 px-8 py-3 text-white font-medium hover:bg-red-800 transition">Daftar Sekarang</button>
                        </div>
                        <div class="placeholder-box flex-1 w-48 h-48"><div class="text-center"><p class="text-sm">QR Code</p><p class="text-xs text-gray-500">Placeholder</p></div></div>
                    </div>
                </div>
                <div class="carousel-slide hidden" data-slide="1">
                    <div class="flex h-full gap-6 bg-gradient-to-r from-blue-400 to-blue-200 rounded-lg p-8 items-center">
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold text-red-700 mb-3">WORKSHOP KEPEMIMPINAN</h1>
                            <p class="text-lg text-gray-800 mb-3 font-semibold">PERIODE MEI 2026</p>
                            <p class="text-gray-700 mb-4">Tingkatkan keterampilan kepemimpinan Anda bersama para pembicara profesional. Terbuka untuk semua mahasiswa!</p>
                            <button class="rounded-full bg-red-700 px-8 py-3 text-white font-medium hover:bg-red-800 transition">Daftar Sekarang</button>
                        </div>
                        <div class="placeholder-box flex-1 w-48 h-48"><div class="text-center"><p class="text-sm">Gambar Workshop</p><p class="text-xs text-gray-500">Placeholder</p></div></div>
                    </div>
                </div>
                <div class="carousel-slide hidden" data-slide="2">
                    <div class="flex h-full gap-6 bg-gradient-to-r from-purple-400 to-purple-200 rounded-lg p-8 items-center">
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold text-red-700 mb-3">KOMPETISI INOVASI</h1>
                            <p class="text-lg text-gray-800 mb-3 font-semibold">PERIODE APRIL - JUNI 2026</p>
                            <p class="text-gray-700 mb-4">Tunjukkan kreativitas Anda dan berkompetisi untuk hadiah jutaan rupiah. Pendaftaran dibuka mulai sekarang!</p>
                            <button class="rounded-full bg-red-700 px-8 py-3 text-white font-medium hover:bg-red-800 transition">Daftar Sekarang</button>
                        </div>
                        <div class="placeholder-box flex-1 w-48 h-48"><div class="text-center"><p class="text-sm">Gambar Inovasi</p><p class="text-xs text-gray-500">Placeholder</p></div></div>
                    </div>
                </div>

                <!-- Dynamic Publikasi Slides (Banner Besar) -->
                @php
                    $publikasiBesar = $publikasis->where('placement', 'besar')->values();
                    $publikasiKeduanya = $publikasis->where('placement', 'keduanya')->values();
                    $bannerPublikasis = $publikasiBesar->merge($publikasiKeduanya);
                @endphp
                @forelse ($bannerPublikasis as $index => $pb)
                    <div class="carousel-slide hidden" data-slide="{{ $index + 3 }}">
                        <div class="flex h-full gap-6 rounded-lg p-8 items-center" style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.8) 0%, rgba(185, 28, 28, 0.8) 100%), url('{{ asset('storage/' . $pb->poster) }}'); background-size: cover; background-position: center;">
                            <div class="flex-1 text-white drop-shadow-lg">
                                <h1 class="text-4xl font-bold mb-3">{{ $pb->judul }}</h1>
                                <p class="text-lg mb-3 font-semibold">{{ $pb->ormawa }}</p>
                                <p class="mb-4 leading-relaxed">{{ Str::limit($pb->caption, 150) }}</p>
                                @if($pb->link)
                                    <a href="{{ Str::startsWith($pb->link, 'http') ? $pb->link : 'https://' . $pb->link }}" target="_blank" class="inline-block rounded-full bg-white px-8 py-3 text-red-700 font-medium hover:bg-gray-100 transition">Lihat Selengkapnya</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
                <button class="carousel-nav-btn prev" onclick="prevSlide()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="carousel-nav-btn next" onclick="nextSlide()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            <div class="carousel-dots" id="carouselDots">
                @php
                    $totalBannerSlides = 3 + $bannerPublikasis->count();
                @endphp
                @for ($i = 0; $i < $totalBannerSlides; $i++)
                    <div class="dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></div>
                @endfor
            </div>
        </div>
    </section>

    <section id="events" class="bg-gray-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Informasi Kegiatan Ormawa</h2>
                <p class="mt-4 text-xl text-gray-500">Temukan berbagai kegiatan seru dari organisasi mahasiswa kami.</p>
            </div>
            <div class="events-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    // Filter publikasi untuk ditampilkan di berita kecil
                    $publikasiKecil = $publikasis->where('placement', 'kecil')->values();
                    $publikasiKeduanya2 = $publikasis->where('placement', 'keduanya')->values();
                    $beritaKecil = $publikasiKecil->merge($publikasiKeduanya2);
                @endphp
                @forelse ($beritaKecil as $index => $p)
                    <div class="event-card rounded-xl bg-white p-5 shadow-md transition-all hover:shadow-xl fade-in" 
                        style="animation-delay: {{ $index * 0.1 }}s;" 
                        data-date="{{ $p->created_at->format('d M Y') }}" 
                        data-author="Admin TOP" 
                        data-description="{{ $p->caption }}" 
                        data-category="KEGIATAN"
                        data-link="{{ $p->link }}">
                        <div class="mb-4 h-52 w-full overflow-hidden rounded-lg bg-gray-100">
                            <img src="{{ asset('storage/' . $p->poster) }}" class="h-full w-full object-cover transition duration-500 hover:scale-110" alt="{{ $p->judul }}">
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">{{ $p->judul }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ $p->ormawa }}</p>
                        <div class="flex items-center justify-between">
                            <button class="read-more-btn text-red-600 font-bold text-xs hover:text-red-800 transition uppercase tracking-wider">Detail Informasi</button>
                            @if($p->link)
                                <a href="{{ Str::startsWith($p->link, 'http') ? $p->link : 'https://' . $p->link }}" target="_blank" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h16"/></svg>
                        </div>
                        <p class="text-gray-500 italic">Belum ada informasi kegiatan terbaru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <div id="newsModal" class="news-modal-backdrop" aria-hidden="true">
        <div class="news-modal-panel" role="dialog" aria-modal="true" aria-labelledby="newsModalTitle">
            <div class="news-modal-header">
                <h2 class="text-xl font-semibold text-gray-900">Detail Informasi</h2>
                <button id="closeNewsModal" type="button" class="news-modal-close" aria-label="Tutup">&times;</button>
            </div>
            <div class="news-modal-body news-modal-content">
                <div id="newsModalImage" class="placeholder-box h-48 w-full overflow-hidden rounded-md"></div>
                <div class="news-modal-text-group">
                    <p id="newsModalCategory" class="news-modal-category text-xs font-semibold uppercase tracking-[0.16em] text-red-700"></p>
                    <h3 id="newsModalTitle" class="news-modal-title text-[1.7rem] font-bold text-gray-900"></h3>
                    <p class="news-modal-organizer text-base text-gray-600"><span id="newsModalOrganizer"></span></p>
                    <p class="news-modal-meta text-sm font-semibold text-gray-800"><span id="newsModalMeta"></span></p>
                </div>
                <p id="newsModalDescription" class="news-modal-description text-sm text-gray-800"></p>
            </div>
        </div>
    </div>

    <footer class="mt-10 text-white" style="background-color: #991b1b; background-image: linear-gradient(90deg, #450a0a 0%, #7f1d1d 50%, #b91c1c 100%);">
        <div class="mx-auto max-w-5xl px-5 py-10 text-sm sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">
                <div class="flex flex-col items-center justify-center gap-1 text-center">
                    <p class="text-base font-semibold">TOPKEMA Telkom</p>
                    <p class="text-white/90">Platform Ormawa dan Prestasi Mahasiswa</p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6">
                    <a href="#home" class="transition hover:text-red-100">Beranda</a>
                    <a href="#events" class="transition hover:text-red-100">Event</a>
                    <a href="{{ route('login') }}" class="transition hover:text-red-100">Login</a>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2 text-white/90 md:justify-end md:gap-3">
                    <p>&copy; 2026 TOP Kema Telkom University</p>
                </div>
            </div>
        </div>
    </footer>

    <a
        href="https://wa.me/6281222003380?text=Halo%20admin%2C%20saya%20ingin%20bertanya%20tentang%20TOP."
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Hubungi via WhatsApp"
        onmouseenter="animateWaHoverIn(this)"
        onmouseleave="animateWaHoverOut(this)"
        ontouchstart="animateWaHoverIn(this)"
        ontouchend="animateWaHoverOut(this)"
        onclick="animateWaClick(this)"
        style="position: fixed; right: 20px; bottom: 20px; z-index: 9999; display: inline-flex; align-items: center; justify-content: center; width: 52px; height: 52px; border-radius: 9999px; background: #2CB100; color: #fff; text-decoration: none; box-shadow: 0 12px 26px rgba(44, 177, 0, 0.32);"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 30px; height: 30px;">
            <path d="M12.04 2C6.49 2 2 6.49 2 12.04c0 1.78.47 3.52 1.35 5.04L2 22l5.06-1.33a9.99 9.99 0 0 0 4.98 1.35H12c5.55 0 10.04-4.49 10.04-10.04C22.04 6.49 17.55 2 12.04 2zm5.83 14.16c-.24.67-1.39 1.28-1.93 1.36-.49.07-1.11.1-1.79-.12-.41-.13-.94-.31-1.63-.61-2.87-1.24-4.74-4.14-4.88-4.33-.13-.18-1.17-1.56-1.17-2.97 0-1.41.74-2.11 1-2.4.26-.29.57-.36.76-.36h.55c.18 0 .42-.07.65.49.24.58.82 2.01.89 2.15.07.14.12.31.02.49-.1.18-.15.29-.3.45-.15.17-.32.37-.45.49-.15.15-.31.31-.13.6.18.29.79 1.3 1.7 2.11 1.16 1.03 2.15 1.35 2.44 1.5.29.15.46.13.63-.08.17-.21.73-.85.93-1.14.2-.29.4-.24.67-.15.27.09 1.72.81 2.01.95.29.15.49.22.56.34.07.12.07.72-.17 1.39z"/>
        </svg>
    </a>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('[data-slide]');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = slides.length;
        let slideInterval;

        function animateWaHoverIn(el) {
            if (!el) return;
            el.style.transform = 'translateY(-4px) scale(1.06)';
            el.style.boxShadow = '0 18px 34px rgba(44, 177, 0, 0.42)';
            el.style.transition = 'transform 180ms ease, box-shadow 180ms ease';
        }

        function animateWaHoverOut(el) {
            if (!el) return;
            el.style.transform = 'translateY(0) scale(1)';
            el.style.boxShadow = '0 12px 28px rgba(44, 177, 0, 0.34)';
            el.style.transition = 'transform 180ms ease, box-shadow 180ms ease';
        }

        function animateWaClick(el) {
            if (!el || !el.animate) return;

            el.animate(
                [
                    { transform: 'scale(1)' },
                    { transform: 'scale(0.88)' },
                    { transform: 'scale(1.08)' },
                    { transform: 'scale(1)' }
                ],
                {
                    duration: 420,
                    easing: 'cubic-bezier(0.22, 1, 0.36, 1)'
                }
            );
        }

        function initCarousel() {
            showSlide(currentSlide);
            startAutoSlide();
        }

        function showSlide(n) {
            if (n >= totalSlides) currentSlide = 0;
            else if (n < 0) currentSlide = totalSlides - 1;
            else currentSlide = n;

            slides.forEach(slide => {
                slide.classList.add('hidden');
                slide.classList.remove('carousel-slide');
            });

            slides[currentSlide].classList.remove('hidden');
            slides[currentSlide].classList.add('carousel-slide');

            dots.forEach((dot, index) => {
                if (index === currentSlide) dot.classList.add('active');
                else dot.classList.remove('active');
            });
        }

        function nextSlide() {
            clearInterval(slideInterval);
            showSlide(currentSlide + 1);
            startAutoSlide();
        }

        function prevSlide() {
            clearInterval(slideInterval);
            showSlide(currentSlide - 1);
            startAutoSlide();
        }

        function goToSlide(n) {
            clearInterval(slideInterval);
            showSlide(n);
            startAutoSlide();
        }

        function startAutoSlide() {
            slideInterval = setInterval(() => {
                currentSlide++;
                showSlide(currentSlide);
            }, 5000);
        }

        function initExpandableEventCards() {
            const cards = document.querySelectorAll('.events-grid > div');
            if (!cards.length) return;

            cards.forEach((card) => {
                card.addEventListener('click', (e) => {
                    if (e.target.closest('a')) return;

                    const isExpanded = card.classList.contains('is-expanded');
                    cards.forEach((item) => item.classList.remove('is-expanded'));

                    if (!isExpanded) {
                        card.classList.add('is-expanded');
                    }
                });
            });
        }

        function initNewsModal() {
            const modal = document.getElementById('newsModal');
            const closeButton = document.getElementById('closeNewsModal');
            const readMoreButtons = document.querySelectorAll('.read-more-btn');

            if (!modal || !closeButton || !readMoreButtons.length) return;

            const categoryEl = document.getElementById('newsModalCategory');
            const titleEl = document.getElementById('newsModalTitle');
            const organizerEl = document.getElementById('newsModalOrganizer');
            const metaEl = document.getElementById('newsModalMeta');
            const descriptionEl = document.getElementById('newsModalDescription');
            const imageEl = document.getElementById('newsModalImage');

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            };

            const openModal = (card) => {
                if (!card) return;

                const category = card.dataset.category || 'BERITA';
                const title = card.querySelector('h3')?.textContent?.trim() || 'Judul berita';
                const organizer = card.querySelector('p')?.textContent?.trim() || '-';
                const description = card.dataset.description || 'Detail berita belum tersedia.';
                const date = card.dataset.date || '-';
                const author = card.dataset.author || organizer;
                const link = card.dataset.link || '';

                const cardImage = card.querySelector('img');
                if (cardImage && imageEl) {
                    imageEl.innerHTML = `<img src="${cardImage.src}" class="h-full w-full object-cover">`;
                } else {
                    imageEl.innerHTML = `<div class="flex h-full w-full items-center justify-center bg-gray-100 text-gray-400 font-bold">NO IMAGE</div>`;
                }

                categoryEl.textContent = category;
                titleEl.textContent = title;
                organizerEl.textContent = organizer;
                
                let metaText = `${date}, oleh ${author}`;
                if (link) {
                    metaText += ` | Link: ${link}`;
                }
                metaEl.textContent = metaText;
                
                descriptionEl.textContent = description;

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            };

            readMoreButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const card = button.closest('.event-card');
                    openModal(card);
                });
            });

            closeButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal();
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initCarousel();
            initExpandableEventCards();
            initNewsModal();
        });
    </script>
</body>
</html>
