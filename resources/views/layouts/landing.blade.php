<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Platform jasa hiper-lokal yang menghubungkan warga dengan penyedia jasa terdekat. Temukan servis AC, kebersihan rumah, les privat, dan banyak layanan lainnya di lingkungan Anda.">
    <meta name="theme-color" content="#2563eb">
    <title>@yield('title', 'Neighbourhood Services - Jasa Profesional Terbaik')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js Intersect Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 selection:bg-brand-500 selection:text-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Liquid Curtain Page Transition Overlay -->
    <div id="page-curtain" class="fixed inset-0 z-[99999] bg-gradient-to-br from-brand-600 via-indigo-600 to-indigo-900 pointer-events-none transition-transform duration-[400ms] ease-[cubic-bezier(0.4,0,0.2,1)] translate-y-0 flex items-center justify-center">
        <!-- Pulse central white brand badge -->
        <div class="flex flex-col items-center gap-4 transition-opacity duration-300" id="curtain-content">
            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-brand-600 shadow-xl border-4 border-white/20 animate-pulse">
                <svg class="w-8 h-8 text-brand-600 animate-spin" style="animation-duration: 3s;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.83l5.5 4.98V18h-2v-6H8.5v6h-2v-7.19L12 5.83z"/></svg>
            </div>
            <span class="text-white font-extrabold text-sm tracking-widest uppercase">Neighbourhood</span>
        </div>
        <!-- Organic wavy liquid bottom SVG edge -->
        <div class="absolute bottom-0 left-0 right-0 h-16 translate-y-full pointer-events-none">
            <svg class="w-full h-full text-indigo-900 fill-current" viewBox="0 0 1440 74" preserveAspectRatio="none">
                <path d="M0,0 C240,60 480,80 720,40 C960,0 1200,20 1440,50 L1440,0 L0,0 Z"></path>
            </svg>
        </div>
    </div>

    <!-- Navigation -->
    <nav :class="{'glass-nav shadow-md py-3': scrolled, 'bg-white/80 backdrop-blur-md py-6 border-b border-gray-100/50': !scrolled}" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center gap-2 sm:gap-3.5 group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white bg-brand-600 shadow-sm transition-all duration-300 transform group-hover:rotate-12">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.83l5.5 4.98V18h-2v-6H8.5v6h-2v-7.19L12 5.83z"/></svg>
                        </div>
                        <span class="text-lg sm:text-2xl font-extrabold tracking-tight text-gray-900 group-hover:text-brand-600 transition-colors">
                            Neighbourhood
                        </span>
                    </a>
                </div>

                <!-- Mobile Navigation Buttons -->
                <div class="flex md:hidden items-center gap-2">
                    @auth
                        @php
                            $dashboardRouteMobile = 'dashboard';
                            if(auth()->user()->isAdmin()) $dashboardRouteMobile = 'admin.dashboard';
                            if(auth()->user()->isPenyedia()) $dashboardRouteMobile = 'penyedia.dashboard';
                            if(auth()->user()->isPelanggan()) $dashboardRouteMobile = 'pelanggan.dashboard';
                        @endphp
                        <a href="{{ route($dashboardRouteMobile) }}"
                           class="px-4 py-2 bg-brand-600 text-white rounded-full font-bold text-sm transition-all shadow-md active:scale-95">
                            Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-3 py-2 text-gray-600 font-semibold text-sm hover:text-brand-600 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 bg-brand-600 text-white rounded-full font-bold text-sm transition-all shadow-md active:scale-95">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        @php
                            $dashboardRoute = 'dashboard';
                            if(auth()->user()->isAdmin()) $dashboardRoute = 'admin.dashboard';
                            if(auth()->user()->isPenyedia()) $dashboardRoute = 'penyedia.dashboard';
                            if(auth()->user()->isPelanggan()) $dashboardRoute = 'pelanggan.dashboard';
                        @endphp
                        <a href="{{ route($dashboardRoute) }}"
                           class="px-6 py-2.5 bg-brand-600 text-white hover:bg-brand-700 rounded-full font-bold transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 relative overflow-hidden group">
                            <span class="relative z-10">Masuk ke Dashboard &rarr;</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="font-semibold text-gray-600 hover:text-brand-600 transition-colors duration-300 relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-brand-600 after:transition-all after:duration-300 hover:after:w-full">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-6 py-2.5 bg-brand-600 text-white hover:bg-brand-700 rounded-full font-bold transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 relative overflow-hidden group">
                           <span class="relative z-10">Daftar Sekarang</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12" data-aos="fade-up">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center text-white transform hover:rotate-12 transition-transform">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.83l5.5 4.98V18h-2v-6H8.5v6h-2v-7.19L12 5.83z"/></svg>
                        </div>
                        <span class="text-2xl font-extrabold text-white tracking-tight">Neighbourhood</span>
                    </div>
                    <p class="text-gray-400 max-w-md leading-relaxed text-sm">
                        Platform hiper-lokal yang menghubungkan penyedia jasa dengan pelanggan di lingkungan yang sama. Memberdayakan ekonomi lokal, membangun komunitas yang lebih erat.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-6 tracking-wider uppercase text-xs">Platform</h3>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-brand-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Cara Kerja</a></li>
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-brand-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Layanan Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-brand-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Keamanan</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-6 tracking-wider uppercase text-xs">Perusahaan</h3>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-brand-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-brand-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center text-sm" data-aos="fade-up" data-aos-delay="200">
                <p>&copy; {{ date('Y') }} Neighbourhood Services. Hak Cipta Dilindungi.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Libraries -->
    <!-- AOS JS (deferred — tidak blokir render) -->
    <script defer src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Typed.js (deferred) -->
    <script defer src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <script>
        // Curtain Page load reveal
        function hideCurtain() {
            const curtain = document.getElementById('page-curtain');
            const content = document.getElementById('curtain-content');
            if (!curtain) return;
            curtain.style.transitionDuration = '400ms';
            if (content) content.style.opacity = '0';
            curtain.style.transform = 'translateY(-105%)';
        }

        // AOS.init() dijalankan setelah deferred script AOS JS selesai dimuat
        document.addEventListener('DOMContentLoaded', function () {
            hideCurtain();
            if (typeof AOS !== 'undefined') {
                AOS.init({ once: true, offset: 50, duration: 800, easing: 'ease-out-cubic' });
            }
        });
        window.addEventListener('pageshow', (e) => { if (e.persisted) hideCurtain(); });

        // Transition link interceptor for single-page style feel
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link) {
                const href = link.getAttribute('href');
                if (href && (
                    href.includes('login') || 
                    href.includes('register') || 
                    href.endsWith('/Neighbourhood_Services') ||
                    (href.includes('127.0.0.1:8000') && (href.endsWith('/') || href.includes('/login') || href.includes('/register')))
                )) {
                    if (e.metaKey || e.ctrlKey) return;
                    
                    e.preventDefault();
                    const curtain = document.getElementById('page-curtain');
                    const content = document.getElementById('curtain-content');
                    if (curtain) {
                        curtain.style.transitionDuration = '0ms';
                        curtain.style.transform = 'translateY(105%)';
                        
                        curtain.offsetHeight; // reflow
                        
                        curtain.style.transitionDuration = '750ms';
                        curtain.style.transform = 'translateY(0)';
                        if (content) content.style.opacity = '1';
                        
                        setTimeout(() => {
                            window.location.href = href;
                        }, 700);
                    } else {
                        window.location.href = href;
                    }
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
