<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Neighbourhood Services</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff6ff', 100: '#dbeafe', 400: '#60a5fa',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) both',
                        'fade-in': 'fadeIn 0.4s ease both',
                        'slide-in-left': 'slideInLeft 0.5s cubic-bezier(0.16,1,0.3,1) both',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(18px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Hide Microsoft Edge / IE native password reveal button */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }

        /* Scrollbar Styling */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* Content scrollbar */
        body::-webkit-scrollbar { width: 6px; }
        body::-webkit-scrollbar-track { background: #f9fafb; }
        body::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 6px; }

        /* Card Hover Lift */
        .card-lift { transition: transform 0.2s cubic-bezier(0.16,1,0.3,1), box-shadow 0.2s ease; }
        .card-lift:hover { transform: translateY(-2px); box-shadow: 0 12px 40px -8px rgba(0,0,0,0.12); }

        /* Stat card glow pulse */
        @keyframes statGlow {
            0%,100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
            50% { box-shadow: 0 0 20px 2px rgba(99,102,241,0.08); }
        }

        /* Stagger fade animations for table rows */
        .stagger-1 { animation: fadeInUp 0.5s 0.05s cubic-bezier(0.16,1,0.3,1) both; }
        .stagger-2 { animation: fadeInUp 0.5s 0.1s cubic-bezier(0.16,1,0.3,1) both; }
        .stagger-3 { animation: fadeInUp 0.5s 0.15s cubic-bezier(0.16,1,0.3,1) both; }
        .stagger-4 { animation: fadeInUp 0.5s 0.2s cubic-bezier(0.16,1,0.3,1) both; }
        .stagger-5 { animation: fadeInUp 0.5s 0.25s cubic-bezier(0.16,1,0.3,1) both; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">

        @if(!auth()->user()->isPelanggan())
            <x-sidebar />
        @endif

        {{-- Main Wrapper --}}
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 ml-0 {{ auth()->user()->isPelanggan() ? '' : 'lg:ml-72' }}">

            <x-navbar />

            <main class="flex-1 p-6 lg:p-8 animate-fade-in-up {{ auth()->user()->isPelanggan() ? 'pb-24 md:pb-8' : '' }}">
                @if(auth()->user()->isPelanggan())
                    <div class="max-w-7xl mx-auto w-full">
                @endif

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="mb-6">
                        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm shadow-emerald-100">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm font-semibold">{{ session('success') }}</span>
                            <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mb-6">
                        <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-sm shadow-rose-100">
                            <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm font-semibold">{{ session('error') }}</span>
                            <button @click="show = false" class="ml-auto text-rose-500 hover:text-rose-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mb-6">
                        <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-2xl shadow-sm">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span class="text-sm font-semibold">{{ session('warning') }}</span>
                            <button @click="show = false" class="ml-auto text-amber-500 hover:text-amber-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </div>
                @endif

                @hasSection('header')
                    <div class="mb-8">
                        <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">@yield('header')</h1>
                        @hasSection('subheader')
                            <p class="text-sm text-gray-500 font-medium mt-1">@yield('subheader')</p>
                        @endif
                    </div>
                @endif

                @yield('content')

                @if(auth()->user()->isPelanggan())
                    </div>
                @endif
            </main>
        </div>
    </div>

    <div id="modals"></div>
    @stack('modals')

    {{-- Bottom Navigation Bar (Mobile only, Pelanggan only) --}}
    @if(auth()->user()->isPelanggan())
    @php $activeOrderCount = auth()->user()->pesananSebagaiPelanggan()->whereIn('status_pesanan', ['menunggu','disetujui'])->count(); @endphp
    <nav class="fixed bottom-0 left-0 right-0 z-50 md:hidden bg-white border-t border-gray-100" style="box-shadow: 0 -4px 20px rgba(0,0,0,0.08);">
        <div class="grid grid-cols-4 h-16 max-w-lg mx-auto">

            {{-- Beranda --}}
            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('pelanggan.dashboard') ? 'text-indigo-600' : 'text-gray-400' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('pelanggan.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] font-bold leading-none">Beranda</span>
            </a>

            {{-- Cari Jasa --}}
            <a href="{{ route('pelanggan.cari') }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('pelanggan.cari') ? 'text-indigo-600' : 'text-gray-400' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('pelanggan.cari') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-[10px] font-bold leading-none">Cari Jasa</span>
            </a>

            {{-- Pesanan --}}
            <a href="{{ route('pelanggan.pesanan.index') }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors relative {{ request()->routeIs('pelanggan.pesanan.*') ? 'text-indigo-600' : 'text-gray-400' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('pelanggan.pesanan.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    @if($activeOrderCount > 0)
                    <span class="absolute -top-1 -right-1.5 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    @endif
                </div>
                <span class="text-[10px] font-bold leading-none">Pesanan</span>
            </a>

            {{-- Profil --}}
            <a href="{{ route('pelanggan.profil') }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('pelanggan.profil') ? 'text-indigo-600' : 'text-gray-400' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('pelanggan.profil') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] font-bold leading-none">Profil</span>
            </a>

        </div>
    </nav>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    @stack('scripts')
</body>
</html>
