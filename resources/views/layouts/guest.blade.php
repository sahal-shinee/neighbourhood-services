<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">
    <title>@yield('title', 'Autentikasi') - Neighbourhood Services</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#0f172a', // premium slate-like dark brand blue
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
    
    <!-- Core utilities and animations -->
    <style>
        [x-cloak] { display: none !important; }

        /* Hide Microsoft Edge / IE native password reveal button */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
        
        /* Premium fadeInUp stagger animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        
        /* Dynamic Morphing Gradient for Brand Panel */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .animate-gradient-shift {
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
        }

        /* Micro-interactions for interactive input fields */
        .premium-input-field {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .premium-input-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.08), 0 0 0 4px rgba(37, 99, 235, 0.12);
        }
        .premium-input-field:hover:not(:focus) {
            transform: translateY(-0.5px);
            border-color: #cbd5e1;
        }

        /* Elegant Split Layout glides on page load */
        @keyframes slideInLeft {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideInUpMobile {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        @media (min-width: 768px) {
            .animate-slide-in-left {
                animation: slideInLeft 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .animate-slide-in-right {
                animation: slideInRight 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        }
        @media (max-width: 767px) {
            .animate-slide-in-mobile {
                animation: slideInUpMobile 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white min-h-screen w-full flex flex-col md:flex-row relative m-0 p-0 overflow-x-hidden" x-data>
    
    <!-- Liquid Curtain Page Transition Overlay -->
    <div id="page-curtain" class="fixed inset-0 z-[99999] bg-gradient-to-br from-brand-600 via-indigo-600 to-indigo-900 pointer-events-none transition-transform duration-[750ms] ease-[cubic-bezier(0.85,0,0.15,1)] translate-y-0 flex items-center justify-center">
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

    <!-- Left Side: Full-height, full-width edge-to-edge Brand Panel with Wavy Divider & Centered Badge (Animated Layout Glide) -->
    <div class="w-full md:w-[42%] lg:w-[38%] bg-gradient-to-b from-brand-600 via-indigo-750 to-indigo-900 animate-gradient-shift animate-slide-in-left animate-slide-in-mobile relative p-10 sm:p-14 md:p-16 text-white flex flex-col justify-between flex-shrink-0 min-h-[50vh] md:min-h-screen z-10">
        <!-- Overflow hidden wrapper for bg pattern and blurs only -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Dot-grid pattern sebagai pengganti gambar Unsplash -->
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px;"></div>

            <!-- Subtle pendaran glowing blurs inside gradient panel -->
            <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-white/5 filter blur-[80px] animate-pulse"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full bg-white/5 filter blur-[80px] animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Wavy/Organic SVG Divider (Matching Reference Image) - Bled perfectly without clipping -->
        <div class="absolute top-0 bottom-0 -right-[47px] w-[48px] hidden md:block z-20 pointer-events-none">
            <svg class="h-full w-full" viewBox="0 0 100 1000" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="wave-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <!-- Gradients exactly matching brand-600 to indigo-850 -->
                        <stop offset="0%" stop-color="#2563eb" />
                        <stop offset="100%" stop-color="#312e81" />
                    </linearGradient>
                </defs>
                <path d="M0,0 C30,80 75,120 60,180 C45,240 85,280 70,340 C55,400 90,440 75,500 C60,560 85,600 70,660 C55,720 90,760 75,820 C60,880 75,920 40,960 C20,980 10,990 0,1000 Z" fill="url(#wave-grad)"/>
            </svg>
        </div>

        <!-- Top spacing/Minimal header -->
        <div class="relative z-10 animate-fade-in-up">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 hover:opacity-85 transition-opacity group transition-link">
                <span class="text-xs font-black tracking-widest text-brand-100/70 uppercase">Neighbourhood Services</span>
            </a>
        </div>

        <!-- Centered Brand Pitch Showcase (Matches Reference Image) -->
        <div class="relative z-10 my-auto text-center flex flex-col items-center justify-center py-6 animate-fade-in-up delay-100">
            <span class="text-brand-100/90 text-sm font-bold uppercase tracking-widest mb-4">Welcome to</span>
            
            <!-- White circle with home SVG (Exactly like reference badge) -->
            <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center text-brand-600 shadow-[0_15px_35px_rgba(37,99,235,0.25)] mb-6 hover:scale-110 hover:shadow-[0_20px_45px_rgba(37,99,235,0.35)] transition-all duration-300 border-[6px] border-white/20 group cursor-pointer">
                <svg class="w-12 h-12 text-brand-600 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            
            <h1 class="text-3xl lg:text-4xl font-black leading-none mb-4 tracking-tight uppercase bg-gradient-to-r from-white via-brand-100 to-white bg-clip-text text-transparent">Neighbourhood</h1>
            
            <p class="text-brand-100 text-xs font-bold leading-relaxed max-w-xs px-2">
                Hubungkan diri Anda dengan penyedia jasa terverifikasi dan kelola kebutuhan harian di sekitar tempat tinggal Anda secara mudah.
            </p>
        </div>

        <!-- Footer trusted section (Matches reference minimal bottom bar) -->
        <div class="relative z-10 text-center text-[10px] font-bold tracking-widest text-brand-200/50 uppercase mt-8 animate-fade-in-up delay-200">
            Koneksi Lokal • Layanan Terpercaya
        </div>
    </div>

    <!-- Right Side: Full-height Edge-to-Edge Form View (Completely fullscreen with no margins - Animated Layout Glide) -->
    <div class="w-full md:w-[58%] lg:w-[62%] bg-slate-50/50 animate-slide-in-right animate-slide-in-mobile flex items-center justify-center p-8 sm:p-12 md:p-16 lg:p-24 relative overflow-y-auto min-h-[50vh] md:min-h-screen">
        <!-- Interactive background glow details behind right panel -->
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-100/10 via-transparent to-purple-100/15 pointer-events-none z-0"></div>
        <div class="absolute top-1/4 right-1/4 w-80 h-80 bg-brand-500/5 rounded-full filter blur-[100px] pointer-events-none z-0"></div>
        <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-purple-500/5 rounded-full filter blur-[100px] pointer-events-none z-0" style="animation-delay: 2.5s;"></div>

        <!-- Centered max width responsive wrapper -->
        <div class="w-full max-w-lg relative z-10 py-6">
            @yield('content')
        </div>
    </div>

    <!-- Liquid Page Transition Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const curtain = document.getElementById('page-curtain');
            const content = document.getElementById('curtain-content');
            if (curtain) {
                // Fade out loader inside curtain slightly early
                setTimeout(() => {
                    if (content) content.style.opacity = '0';
                }, 150);
                // Sweep curtain up to reveal page
                setTimeout(() => {
                    curtain.style.transform = 'translateY(-105%)';
                }, 200);
            }
        });

        // Intercept transitions for custom organic sweep
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link) {
                const href = link.getAttribute('href');
                // Detect home or auth links
                if (href && (
                    href.includes('login') || 
                    href.includes('register') || 
                    href === '/' || 
                    href.endsWith('/Neighbourhood_Services') ||
                    (href.includes('127.0.0.1:8000') && (href.endsWith('/') || href.includes('/login') || href.includes('/register')))
                )) {
                    if (e.metaKey || e.ctrlKey) return;
                    
                    e.preventDefault();
                    const curtain = document.getElementById('page-curtain');
                    const content = document.getElementById('curtain-content');
                    if (curtain) {
                        // Reset curtain state to slide down from top
                        curtain.style.transitionDuration = '0ms';
                        curtain.style.transform = 'translateY(105%)';
                        
                        // Trigger reflow
                        curtain.offsetHeight;
                        
                        // Animate down to fully cover
                        curtain.style.transitionDuration = '750ms';
                        curtain.style.transform = 'translateY(0)';
                        if (content) content.style.opacity = '1';
                        
                        // Relocate after sweep covers page
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
</body>
</html>
