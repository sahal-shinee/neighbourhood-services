<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-xl border-b border-gray-900/[0.06] sticky top-0 z-40 transition-all duration-300" style="box-shadow: 0 1px 0 rgba(0,0,0,0.06), 0 4px 24px rgba(0,0,0,0.04);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-[72px]">

            @if(auth()->user()->isPelanggan())
                {{-- CUSTOMER: Marketplace Navbar --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('pelanggan.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.83l5.5 4.98V18h-2v-6H8.5v6h-2v-7.19L12 5.83z"/></svg>
                        </div>
                        <span class="text-lg font-extrabold text-gray-900 tracking-tight">Neighbourhood</span>
                    </a>
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('pelanggan.dashboard') }}" class="px-4 py-2 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('pelanggan.dashboard') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Beranda</a>
                        <a href="{{ route('pelanggan.cari') }}" class="px-4 py-2 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('pelanggan.cari') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Cari Jasa</a>
                        <a href="{{ route('pelanggan.pesanan.index') }}" class="px-4 py-2 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('pelanggan.pesanan.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Riwayat Pesanan</a>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Cart Icon --}}
                    <a href="{{ route('pelanggan.pesanan.index') }}" class="relative p-2.5 rounded-xl text-gray-400 hover:bg-gray-50 hover:text-indigo-600 transition-all" title="Pesanan Saya">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @php $activeCount = auth()->user()->pesananSebagaiPelanggan()->whereIn('status_pesanan', ['menunggu','disetujui'])->count(); @endphp
                        @if($activeCount > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </a>

                    {{-- Notification Bell --}}
                    @include('components.notification-bell')

                    <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-sm font-bold text-gray-900 leading-tight">{{ Auth::user()->nama_lengkap }}</span>
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Pelanggan</span>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center focus:outline-none group">
                                <div class="w-10 h-10 rounded-full border-2 border-gray-200 group-hover:border-indigo-400 transition-all duration-200 bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center font-bold text-indigo-600 uppercase overflow-hidden shadow-sm">
                                    @if(auth()->user()->foto_profil)
                                        <img src="{{ auth()->user()->foto_profil_url }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                                    @endif
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->nama_lengkap }}</p>
                                <p class="text-xs text-indigo-500 font-semibold">Pelanggan</p>
                            </div>
                            <x-dropdown-link :href="route('pelanggan.profil')"><div class="flex items-center gap-2.5"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg><span class="font-semibold text-gray-700">Profil Saya</span></div></x-dropdown-link>
                            <x-dropdown-link :href="route('pelanggan.cari')"><div class="flex items-center gap-2.5"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><span class="font-semibold text-gray-700">Cari Jasa</span></div></x-dropdown-link>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span class="font-bold">Keluar</span>
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

            @else
                {{-- ADMIN & PROVIDER: Sidebar-Integrated Top Navbar --}}

                {{-- Left: Mobile menu button --}}
                <div class="flex items-center gap-3 lg:hidden">
                    <button @click="sidebarOpen = true" class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 focus:outline-none transition-colors">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <span class="text-base font-extrabold text-gray-900">Neighbourhood</span>
                </div>

                {{-- Left Desktop: System status indicator --}}
                <div class="hidden lg:flex items-center gap-4">
                    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200/60 px-3.5 py-1.5 rounded-full">
                        <span class="relative flex w-2 h-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-bold text-emerald-700">Sistem Berjalan</span>
                    </div>
                </div>

                {{-- Right: Notifications + Profile --}}
                <div class="flex items-center gap-3">

                    {{-- Notification Bell --}}
                    @include('components.notification-bell')

                    <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-sm font-bold text-gray-900 leading-tight">{{ Auth::user()->nama_lengkap }}</span>
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ Auth::user()->peran }}</span>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center focus:outline-none group">
                                <div class="w-10 h-10 rounded-full border-2 border-gray-200 group-hover:border-indigo-400 transition-all duration-200 bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center font-bold text-indigo-600 uppercase overflow-hidden shadow-sm">
                                    @if(auth()->user()->foto_profil)
                                        <img src="{{ auth()->user()->foto_profil_url }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                                    @endif
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->nama_lengkap }}</p>
                                <p class="text-xs text-indigo-500 font-semibold capitalize">{{ auth()->user()->peran }}</p>
                            </div>
                            @if(auth()->user()->isPenyedia())
                                <x-dropdown-link :href="route('penyedia.profil')"><div class="flex items-center gap-2.5"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg><span class="font-semibold text-gray-700">Profil Saya</span></div></x-dropdown-link>
                                <x-dropdown-link :href="route('penyedia.portofolio.index')"><div class="flex items-center gap-2.5"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span class="font-semibold text-gray-700">Portofolio</span></div></x-dropdown-link>
                            @endif
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span class="font-bold">Keluar</span>
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

        </div>
    </div>
</nav>
