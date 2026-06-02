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

            <main class="flex-1 p-6 lg:p-8 animate-fade-in-up">
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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    @stack('scripts')
</body>
</html>
