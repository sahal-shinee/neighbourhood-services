{{-- Mobile sidebar backdrop --}}
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm z-40 lg:hidden"
     @click="sidebarOpen = false" x-cloak></div>

<style>
.sidebar-glow { box-shadow: 4px 0 40px rgba(0,0,0,0.3), inset -1px 0 0 rgba(255,255,255,0.04); }
.nav-active-glow { box-shadow: 0 0 20px rgba(99,102,241,0.25); }
.logo-pulse { animation: logoPulse 3s ease-in-out infinite; }
@keyframes logoPulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(99,102,241,0.4); }
    50% { box-shadow: 0 0 0 8px rgba(99,102,241,0); }
}
.sidebar-item-enter { animation: sidebarItemIn 0.5s cubic-bezier(0.16,1,0.3,1) forwards; opacity: 0; }
@keyframes sidebarItemIn {
    from { opacity: 0; transform: translateX(-12px); }
    to { opacity: 1; transform: translateX(0); }
}
.banding-pulse { animation: bandingBadgePulse 2s ease-in-out infinite; }
@keyframes bandingBadgePulse {
    0%,100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}
</style>

{{-- Sidebar --}}
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
     class="fixed inset-y-0 left-0 z-50 w-72 sidebar-glow bg-[#0f1117] text-gray-300 transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col border-r border-white/[0.06] overflow-hidden">

    {{-- Ambient background orb --}}
    <div class="pointer-events-none absolute top-0 left-0 w-full h-full overflow-hidden">
        <div class="absolute -top-20 -left-10 w-72 h-72 bg-indigo-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-40 h-40 bg-blue-600/8 rounded-full blur-2xl"></div>
    </div>

    {{-- Logo Header --}}
    <div class="relative z-10 flex items-center justify-between h-[72px] px-6 border-b border-white/[0.06] flex-shrink-0">
        <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
            <div class="logo-pulse w-9 h-9 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm0 2.83l5.5 4.98V18h-2v-6H8.5v6h-2v-7.19L12 5.83z"/></svg>
            </div>
            <div>
                <span class="text-base font-extrabold text-white tracking-tight leading-tight">Neighbourhood</span>
                <span class="block text-[10px] font-bold text-indigo-400 tracking-widest uppercase leading-tight">Services</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-white p-1 rounded-lg hover:bg-white/5 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- User Profile Card --}}
    <div class="relative z-10 px-4 pt-5 pb-3 flex-shrink-0">
        <div class="flex items-center gap-3 bg-white/[0.04] border border-white/[0.07] rounded-2xl p-3.5 hover:bg-white/[0.06] transition-colors">
            <div class="w-10 h-10 rounded-xl border border-white/10 bg-gradient-to-br from-indigo-500/20 to-blue-600/20 flex items-center justify-center text-sm font-bold text-white uppercase overflow-hidden flex-shrink-0">
                @if(auth()->user()->foto_profil)
                    <img src="{{ auth()->user()->foto_profil_url }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <span class="text-indigo-300">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate leading-tight">{{ auth()->user()->nama_lengkap }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></span>
                    <p class="text-[10px] font-extrabold text-indigo-400 uppercase tracking-widest truncate">{{ auth()->user()->peran }}</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $activeClass  = "relative flex items-center gap-3 px-3.5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl nav-active-glow transition-all duration-200";
        $inactiveClass = "flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-gray-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-all duration-200 group";
        $isBanding = auth()->user()->isPenyedia() && auth()->user()->status_verifikasi === 'pending' && auth()->user()->pesan_banding;
    @endphp

    {{-- Navigation --}}
    <nav class="relative z-10 flex-1 px-4 space-y-1 overflow-y-auto py-2 custom-scroll">
        @if(auth()->user()->isAdmin())
            {{-- Admin Navigation --}}
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('admin.dashboard'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </span>
                <span>Dashboard</span>
            </a>

            <div class="pt-5 pb-1.5">
                <p class="px-3.5 text-[9px] font-black tracking-[0.18em] text-gray-600 uppercase">Verifikasi & Layanan</p>
            </div>

            <a href="{{ route('admin.verifikasi') }}" class="{{ request()->routeIs('admin.verifikasi') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('admin.verifikasi'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.verifikasi') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <span class="flex-1">Verifikasi Penyedia</span>
                @php $pendingCount = \App\Models\Pengguna::penyedia()->where('status_verifikasi','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto text-[10px] font-black bg-red-500 text-white px-2 py-0.5 rounded-full shadow-sm shadow-red-500/40">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.pesanan') }}" class="{{ request()->routeIs('admin.pesanan') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('admin.pesanan'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.pesanan') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
                <span>Daftar Pesanan</span>
            </a>

            <div class="pt-5 pb-1.5">
                <p class="px-3.5 text-[9px] font-black tracking-[0.18em] text-gray-600 uppercase">Manajemen Data</p>
            </div>

            <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('admin.pengguna.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.pengguna.*') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <span>Kelola Pengguna</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('admin.kategori.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.kategori.*') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                </span>
                <span>Kategori Jasa</span>
            </a>

            <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('admin.laporan.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.laporan.*') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                <span>Laporan Pengguna</span>
                @php $newReports = \App\Models\Laporan::where('status','baru')->count(); @endphp
                @if($newReports > 0)
                <span class="ml-auto bg-red-500 text-white text-[9px] font-black rounded-full w-4 h-4 flex items-center justify-center flex-shrink-0">{{ $newReports > 9 ? '9+' : $newReports }}</span>
                @endif
            </a>

        @elseif(auth()->user()->isPenyedia())
            {{-- Penyedia Navigation --}}
            <a href="{{ route('penyedia.dashboard') }}" class="{{ request()->routeIs('penyedia.dashboard') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('penyedia.dashboard'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('penyedia.dashboard') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </span>
                <span>Ringkasan Kinerja</span>
            </a>

            <div class="pt-5 pb-1.5">
                <p class="px-3.5 text-[9px] font-black tracking-[0.18em] text-gray-600 uppercase">Menu Jasa</p>
            </div>

            <a href="{{ route('penyedia.pesanan.index') }}" class="{{ request()->routeIs('penyedia.pesanan.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('penyedia.pesanan.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('penyedia.pesanan.*') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </span>
                @php $menungguCount = \App\Models\PesananJasa::whereHas('jasa', fn($q) => $q->where('id_penyedia', auth()->user()->id_pengguna))->where('status_pesanan','menunggu')->count(); @endphp
                <span class="flex-1">Pesanan Masuk</span>
                @if($menungguCount > 0)
                    <span class="ml-auto text-[10px] font-black bg-amber-500 text-white px-2 py-0.5 rounded-full">{{ $menungguCount }}</span>
                @endif
            </a>

            <a href="{{ route('penyedia.jadwal') }}" class="{{ request()->routeIs('penyedia.jadwal') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('penyedia.jadwal'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('penyedia.jadwal') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <span>Kalender Jadwal</span>
            </a>

            <a href="{{ route('penyedia.jasa.index') }}" class="{{ request()->routeIs('penyedia.jasa.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('penyedia.jasa.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('penyedia.jasa.*') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <span>Layanan Saya</span>
            </a>

            <a href="{{ route('penyedia.portofolio.index') }}" class="{{ request()->routeIs('penyedia.portofolio.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('penyedia.portofolio.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('penyedia.portofolio.*') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <span>Portofolio</span>
            </a>

            <div class="pt-5 pb-1.5">
                <p class="px-3.5 text-[9px] font-black tracking-[0.18em] text-gray-600 uppercase">Profil & Akun</p>
            </div>

            <a href="{{ route('penyedia.profil') }}" class="{{ request()->routeIs('penyedia.profil') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('penyedia.profil'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <span class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('penyedia.profil') ? '' : 'group-hover:scale-110 transition-transform' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                <span class="flex-1">Profil Saya</span>
                @if($isBanding)
                    <span class="banding-pulse text-[9px] font-black bg-amber-500 text-white px-1.5 py-0.5 rounded-full">BANDING</span>
                @endif
            </a>

        @elseif(auth()->user()->isPelanggan())
            <a href="{{ route('pelanggan.dashboard') }}" class="{{ request()->routeIs('pelanggan.dashboard') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('pelanggan.dashboard'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Beranda</span>
            </a>
            <div class="pt-5 pb-1.5"><p class="px-3.5 text-[9px] font-black tracking-[0.18em] text-gray-600 uppercase">Layanan & Order</p></div>
            <a href="{{ route('pelanggan.cari') }}" class="{{ request()->routeIs('pelanggan.cari') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('pelanggan.cari'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Cari Jasa</span>
            </a>
            <a href="{{ route('pelanggan.pesanan.index') }}" class="{{ request()->routeIs('pelanggan.pesanan.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('pelanggan.pesanan.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Riwayat Pesanan</span>
            </a>
            <a href="{{ route('pelanggan.favorit.index') }}" class="{{ request()->routeIs('pelanggan.favorit.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('pelanggan.favorit.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Jasa Favorit</span>
            </a>
            <a href="{{ route('pelanggan.laporan.index') }}" class="{{ request()->routeIs('pelanggan.laporan.*') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('pelanggan.laporan.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                <span class="flex-1">Laporan Saya</span>
                @php $myLaporan = \App\Models\Laporan::where('id_pelapor', auth()->user()->id_pengguna)->where('status','baru')->count(); @endphp
                @if($myLaporan > 0)
                    <span class="ml-auto text-[9px] font-black bg-blue-500 text-white px-1.5 py-0.5 rounded-full">{{ $myLaporan }}</span>
                @endif
            </a>
            <div class="pt-5 pb-1.5"><p class="px-3.5 text-[9px] font-black tracking-[0.18em] text-gray-600 uppercase">Profil & Akun</p></div>
            <a href="{{ route('pelanggan.profil') }}" class="{{ request()->routeIs('pelanggan.profil') ? $activeClass : $inactiveClass }}">
                @if(request()->routeIs('pelanggan.profil'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-white rounded-r-full"></span>
                @endif
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Profil Saya</span>
            </a>
        @endif
    </nav>

    {{-- Logout --}}
    <div class="relative z-10 p-4 border-t border-white/[0.06] flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full gap-3 px-3.5 py-3 text-sm font-bold text-rose-400/80 rounded-xl hover:bg-rose-500/10 hover:text-rose-300 transition-all duration-200 group">
                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar dari Akun
            </button>
        </form>
    </div>
</div>
