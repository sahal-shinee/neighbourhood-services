@extends('layouts.app')
@section('title', 'Dashboard Pelanggan')

@push('styles')
<style>
    @keyframes float-slow    { 0%,100%{transform:translateY(0) scale(1)}  50%{transform:translateY(-14px) scale(1.06)} }
    @keyframes float-reverse { 0%,100%{transform:translateY(0) scale(1.06)} 50%{transform:translateY(14px) scale(1)} }
    .animate-float-1 { animation: float-slow 7s ease-in-out infinite; }
    .animate-float-2 { animation: float-reverse 9s ease-in-out infinite; }
    @keyframes wave { 0%,100%{transform:rotate(-5deg)} 50%{transform:rotate(10deg)} }
    .animate-wave { animation: wave 1.4s ease-in-out infinite; display:inline-block; transform-origin: 70% 70%; }
</style>
@endpush

@section('content')

@php
    $hour = \Carbon\Carbon::now()->format('H');
    $greeting = 'Selamat Pagi';
    if ($hour >= 11 && $hour < 15)      $greeting = 'Selamat Siang';
    elseif ($hour >= 15 && $hour < 19)  $greeting = 'Selamat Sore';
    elseif ($hour >= 19 || $hour < 5)   $greeting = 'Selamat Malam';
@endphp

{{-- Greeting Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <span class="text-xs font-black text-brand-600 uppercase tracking-widest block mb-1">Beranda Layanan</span>
        <h2 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
            {{ $greeting }}, {{ explode(' ', auth()->user()->nama_lengkap)[0] }}!
            <span class="animate-wave text-2xl" aria-hidden="true">
                <svg class="w-7 h-7 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M7.5 2.25a.75.75 0 01.75.75v.75a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zm3 0a.75.75 0 01.75.75v.75a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zm3 0a.75.75 0 01.75.75v.75a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM4.5 6.75a.75.75 0 000 1.5h15a.75.75 0 000-1.5h-15zm-.75 3.75A.75.75 0 014.5 9.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm.75 3a.75.75 0 000 1.5h15a.75.75 0 000-1.5h-15zm-.75 3.75A.75.75 0 014.5 16.5h9a.75.75 0 010 1.5h-9a.75.75 0 01-.75-.75z"/></svg>
            </span>
        </h2>
    </div>
    <div class="text-left sm:text-right">
        <span class="text-xs font-bold text-gray-400 block">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100/50 px-2.5 py-1 rounded-lg mt-1 inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span>
            Sesi Aktif
        </span>
    </div>
</div>

{{-- Search Banner --}}
<div class="mb-10">
    <div class="bg-gradient-to-r from-brand-600 via-indigo-600 to-indigo-800 rounded-[2.5rem] p-8 sm:p-12 relative overflow-hidden shadow-xl shadow-brand-500/10 border border-white/10">
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full filter blur-2xl pointer-events-none animate-float-1"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-brand-500/20 rounded-full filter blur-3xl pointer-events-none animate-float-2"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.08)_0%,transparent_50%)] pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl">
            <span class="text-[10px] font-black uppercase tracking-widest text-brand-200 bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/10 mb-4 inline-block">
                Layanan Hyperlocal Terpercaya
            </span>
            <h2 class="text-3xl sm:text-4xl font-black tracking-tight mb-3 leading-tight text-white">Butuh bantuan di sekitar rumah?</h2>
            <p class="text-sm font-semibold text-brand-100/90 mb-8 max-w-lg">Pesan layanan dari penyedia jasa profesional yang berada tepat di lingkungan Anda.</p>

            <form action="{{ route('pelanggan.cari') }}" method="GET"
                  class="flex flex-col sm:flex-row items-center gap-3 bg-white p-2.5 rounded-2xl shadow-lg w-full">
                <div class="relative flex-grow flex items-center w-full">
                    <span class="absolute left-3.5 text-gray-400 flex-shrink-0 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                    </span>
                    <input type="text" name="keyword" placeholder="Cari: Servis AC, Guru Les, Cuci Mobil..."
                           class="w-full bg-transparent border-none focus:ring-0 text-gray-900 placeholder-gray-400 font-semibold pl-11 pr-3 py-3 outline-none text-sm sm:text-base">
                </div>
                <button type="submit"
                    class="w-full sm:w-auto bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white px-8 py-3.5 rounded-xl font-extrabold transition-all shadow-[0_4px_15px_rgba(37,99,235,0.3)] whitespace-nowrap text-sm">
                    Cari Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
    <x-stat-card title="Total Pesanan" value="{{ $stats['total_pesanan'] }}" color="indigo">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </x-stat-card>
    <x-stat-card title="Pesanan Aktif" value="{{ $stats['pesanan_aktif'] }}" color="amber">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </x-stat-card>
    <x-stat-card title="Ulasan Ditulis" value="{{ $stats['ulasan_ditulis'] }}" color="emerald">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    </x-stat-card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Recent Orders --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-[0_5px_20px_rgba(0,0,0,0.03)] p-8">
            <div class="flex justify-between items-center mb-7 pb-5 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Aktivitas Pemesanan</h3>
                    <p class="text-xs font-semibold text-gray-400 mt-1">Pesanan jasa terbaru yang Anda lakukan</p>
                </div>
                <a href="{{ route('pelanggan.pesanan.index') }}"
                   class="text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors bg-brand-50 px-4 py-2.5 rounded-xl border border-brand-100/50 hover:border-brand-200 flex items-center gap-1.5">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="space-y-3.5">
                @forelse($pesanan_terbaru as $i => $pesanan)
                    <a href="{{ route('pelanggan.pesanan.show', $pesanan->id_pesanan) }}"
                       class="block p-4 bg-gray-50/60 hover:bg-white hover:shadow-[0_4px_20px_rgba(0,0,0,0.05)] rounded-2xl border border-gray-100 hover:border-brand-200 transition-all duration-300 group"
                       style="animation: fadeInUp 0.4s {{ $i * 0.06 + 0.1 }}s cubic-bezier(0.16,1,0.3,1) both;">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <img src="{{ $pesanan->jasa->penyedia->foto_profil_url }}"
                                     class="w-11 h-11 rounded-xl object-cover border border-gray-100 shadow-sm flex-shrink-0" alt="">
                                <div class="min-w-0">
                                    <h4 class="font-extrabold text-gray-900 group-hover:text-brand-600 transition-colors truncate text-sm sm:text-base">{{ $pesanan->jasa->nama_jasa }}</h4>
                                    <p class="text-xs font-bold text-gray-400 mt-0.5 truncate">{{ $pesanan->jasa->penyedia->nama_lengkap }}</p>
                                    <div class="flex items-center gap-1 text-[10px] font-black text-brand-600 mt-1.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ \Carbon\Carbon::parse($pesanan->tanggal_booking)->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end flex-shrink-0">
                                <x-badge :variant="$pesanan->status_variant" class="font-black px-3 py-1 text-[10px] uppercase tracking-wider">{{ $pesanan->status_label }}</x-badge>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-14 bg-white rounded-2xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4"
                             style="animation: float-slow 5s ease-in-out infinite;">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">Belum ada riwayat pesanan</h4>
                        <p class="text-xs text-gray-400 font-semibold mt-1">Layanan yang Anda pesan akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Kategori Terpopuler --}}
    <div>
        <div class="mb-5 px-1">
            <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Kategori Layanan</h3>
            <p class="text-xs font-semibold text-gray-400 mt-1">Temukan jasa terdekat sesuai kebutuhan.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            @php $kategori = \App\Models\Kategori::inRandomOrder()->take(4)->get(); @endphp

            @forelse($kategori as $i => $k)
                <a href="{{ route('pelanggan.cari', ['kategori' => $k->nama_kategori]) }}"
                   class="bg-white rounded-[2rem] p-5 text-center border border-gray-100
                          hover:border-brand-400 shadow-[0_2px_12px_rgba(0,0,0,0.03)]
                          hover:shadow-[0_10px_28px_rgba(37,99,235,0.08)]
                          hover:-translate-y-1.5 transition-all group duration-300"
                   style="animation: fadeInUp 0.4s {{ $i * 0.08 + 0.2 }}s cubic-bezier(0.16,1,0.3,1) both;">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-3xl mx-auto shadow-sm
                                group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 mb-3.5 border border-indigo-100/60">
                        {{ $k->ikon_kategori }}
                    </div>
                    <p class="text-xs font-extrabold text-gray-800 line-clamp-1 group-hover:text-brand-600 transition-colors uppercase tracking-wide">{{ $k->nama_kategori }}</p>
                </a>
            @empty
                <div class="col-span-2 text-center py-10 text-xs font-semibold text-gray-400 bg-white border border-dashed border-gray-200 rounded-3xl">
                    Belum ada kategori.
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
