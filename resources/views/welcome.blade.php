<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HOME &mdash; TOP Telkom Ormawa &amp; Prestasi</title>

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
        .carousel-container { position: relative; overflow: hidden; height: 400px; border-radius: 16px; }
        .carousel-slide { position: absolute; width: 100%; height: 100%; top: 0; left: 0; }
        .carousel-dots { display: flex; justify-content: center; gap: 8px; margin-top: 16px; }
        .dot { width: 12px; height: 12px; border-radius: 50%; background-color: #d1d5db; cursor: pointer; transition: all 0.3s ease; }
        .dot.active { background-color: #dc2626; width: 32px; border-radius: 6px; }
        .carousel-nav-btn { position: absolute; top: 50%; transform: translateY(-50%); background-color: rgba(255, 255, 255, 0.8); border: none; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; z-index: 10; }
        .carousel-nav-btn:hover { background-color: rgba(255, 255, 255, 1); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
        .carousel-nav-btn.prev { left: 16px; }
        .carousel-nav-btn.next { right: 16px; }

        .dropdown { position: relative; display: inline-block; }
        .dropdown-content { display: none; position: absolute; right: 0; background-color: white; min-width: 160px; box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.1); padding: 8px 0; z-index: 1; border-radius: 8px; overflow: hidden; }
        .dropdown-content a { color: #111827; padding: 12px 16px; text-decoration: none; display: block; transition: background-color 0.3s ease; }
        .dropdown-content a:hover { background-color: #f3f4f6; }
        .dropdown-toggle::after { content: ''; display: inline-block; width: 6px; height: 6px; border-right: 2px solid currentColor; border-bottom: 2px solid currentColor; transform: rotate(45deg) translateY(-2px); margin-left: 8px; transition: transform 0.3s ease; }
        .dropdown.active .dropdown-toggle::after { transform: rotate(-135deg) translateY(0px); }
        .dropdown.active .dropdown-content { display: block; animation: fadeInUp 0.3s ease-out; }

        .placeholder-box { background-color: #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #6b7280; font-weight: 500; }
        .fade-in { animation: fadeInUp 0.6s ease-out; }
    </style>
</head>
<body class="bg-white">
    <nav class="border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('top_logo.png') }}" alt="TOP" class="h-10 w-auto object-contain">
                </div>
                <div class="flex items-center gap-4">
                    <a href="#home" class="text-sm font-medium text-gray-700 transition hover:text-gray-900">Home</a>
                    <a href="#about" class="text-sm font-medium text-gray-700 transition hover:text-gray-900">Tentang</a>
                    <a href="#events" class="text-sm font-medium text-gray-700 transition hover:text-gray-900">Event</a>
                    <div class="dropdown">
                        <button class="dropdown-toggle flex items-center rounded-full bg-red-700 px-6 py-2 text-sm font-medium text-white transition hover:bg-red-800">
                            Login
                        </button>
                        <div class="dropdown-content">
                            <a href="{{ route('login') }}">Login User</a>
                            <a href="#admin">Admin Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="bg-gradient-to-b from-yellow-50 to-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="carousel-container mb-8">
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
            <div class="carousel-dots">
                <div class="dot active" onclick="goToSlide(0)"></div>
                <div class="dot" onclick="goToSlide(1)"></div>
                <div class="dot" onclick="goToSlide(2)"></div>
            </div>
        </div>
    </section>

    <section id="events" class="bg-gray-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Webinar & Event Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="rounded-lg bg-white p-6 shadow-md transition hover:shadow-lg fade-in">
                    <div class="placeholder-box mb-4 h-48 w-full"><span class="text-gray-500">Foto Event</span></div>
                    <div class="mb-2 flex items-center gap-2"><svg class="h-5 w-5 text-red-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" clip-rule="evenodd"></path></svg><span class="text-sm font-semibold">WEBINAR</span></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Webinar Kewirausahaan</h3>
                    <p class="text-sm text-gray-600 mb-4">UKM Manggala</p>
                    <a href="#" class="text-red-700 font-medium text-sm hover:underline">Read More →</a>
                </div>
                <div class="rounded-lg bg-white p-6 shadow-md transition hover:shadow-lg fade-in" style="animation-delay: 0.1s;">
                    <div class="placeholder-box mb-4 h-48 w-full"><span class="text-gray-500">Foto Event</span></div>
                    <div class="mb-2 flex items-center gap-2"><svg class="h-5 w-5 text-red-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" clip-rule="evenodd"></path></svg><span class="text-sm font-semibold">WEBINAR</span></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Webinar Kewirausahaan</h3>
                    <p class="text-sm text-gray-600 mb-4">UKM Manggala</p>
                    <a href="#" class="text-red-700 font-medium text-sm hover:underline">Read More →</a>
                </div>
                <div class="rounded-lg bg-white p-6 shadow-md transition hover:shadow-lg fade-in" style="animation-delay: 0.2s;">
                    <div class="placeholder-box mb-4 h-48 w-full"><span class="text-gray-500">Foto Event</span></div>
                    <div class="mb-2 flex items-center gap-2"><svg class="h-5 w-5 text-red-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" clip-rule="evenodd"></path></svg><span class="text-sm font-semibold">WEBINAR</span></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Webinar Kewirausahaan</h3>
                    <p class="text-sm text-gray-600 mb-4">UKM Manggala</p>
                    <a href="#" class="text-red-700 font-medium text-sm hover:underline">Read More →</a>
                </div>
                <div class="rounded-lg bg-white p-6 shadow-md transition hover:shadow-lg fade-in" style="animation-delay: 0.3s;">
                    <div class="placeholder-box mb-4 h-48 w-full"><span class="text-gray-500">Foto Event</span></div>
                    <div class="mb-2 flex items-center gap-2"><svg class="h-5 w-5 text-red-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" clip-rule="evenodd"></path></svg><span class="text-sm font-semibold">WEBINAR</span></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Webinar Kewirausahaan</h3>
                    <p class="text-sm text-gray-600 mb-4">UKM Manggala</p>
                    <a href="#" class="text-red-700 font-medium text-sm hover:underline">Read More →</a>
                </div>
            </div>
            <div class="mt-12 flex justify-center">
                <button class="rounded-full bg-red-700 px-8 py-3 text-white font-medium hover:bg-red-800 transition">
                    Lihat Event Lainnya
                </button>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h4 class="font-bold text-lg mb-3">TOP Telkom</h4>
                    <p class="text-sm text-gray-400">Platform resmi Ormawa & Prestasi Universitas Telkom</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-3">Menu</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Home</a></li>
                        <li><a href="#" class="hover:text-white transition">Tentang</a></li>
                        <li><a href="#" class="hover:text-white transition">Event</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-3">Kontak</h4>
                    <p class="text-sm text-gray-400 mb-2">📧 info@telkomuniversity.ac.id</p>
                    <p class="text-sm text-gray-400">📞 (022) 7564108</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8">
                <p class="text-center text-sm text-gray-400">&copy; 2026 TOP Telkom University. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('[data-slide]');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = slides.length;
        let slideInterval;

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

        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            if (toggle) {
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdown.classList.toggle('active');
                });
            }
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        });

        document.addEventListener('DOMContentLoaded', initCarousel);
    </script>
</body>
</html>
