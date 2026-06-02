@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-805 text-sm font-semibold flex items-center gap-3 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
@endif

<!-- Page Header and Status Interactive Tabs -->
<div class="mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Riwayat Pesanan</h2>
        <p class="text-xs font-semibold text-gray-400 mt-1">Lacak status transaksi atau bersihkan riwayat pesanan Anda.</p>
    </div>

    <!-- Dynamic Sliding Tab Filters -->
    <div class="flex flex-wrap items-center gap-2 bg-gray-100/70 p-1.5 rounded-2xl border border-gray-200/50">
        @php
            $currentStatus = request('status');
            $tabs = [
                ''           => 'Semua',
                'menunggu'   => 'Menunggu',
                'disetujui'  => 'Disetujui',
                'selesai'    => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ];
        @endphp
        @foreach($tabs as $val => $label)
            <a href="{{ route('pelanggan.pesanan.index', array_filter(['status' => $val ?: null, 'dari' => request('dari'), 'sampai' => request('sampai')])) }}"
                class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-300 whitespace-nowrap {{ $currentStatus === $val ? 'bg-white text-brand-650 shadow-sm border border-gray-200/30' : 'text-gray-500 hover:text-gray-900' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

{{-- Filter Tanggal --}}
<form method="GET" action="{{ route('pelanggan.pesanan.index') }}" class="mb-6 flex flex-wrap items-end gap-3 bg-white border border-gray-100 rounded-2xl p-4">
    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
    <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Dari Tanggal</label>
        <input type="date" name="dari" value="{{ request('dari') }}"
            class="bg-gray-50 border border-gray-200 text-gray-800 rounded-xl px-3 py-2 text-sm font-medium focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 outline-none">
    </div>
    <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Sampai Tanggal</label>
        <input type="date" name="sampai" value="{{ request('sampai') }}"
            class="bg-gray-50 border border-gray-200 text-gray-800 rounded-xl px-3 py-2 text-sm font-medium focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 outline-none">
    </div>
    <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl transition-all">Terapkan</button>
    @if(request('dari') || request('sampai'))
        <a href="{{ route('pelanggan.pesanan.index', request('status') ? ['status' => request('status')] : []) }}"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-xl transition-all">Reset</a>
    @endif
</form>

<!-- History List -->
<div class="space-y-5">
    @forelse($pesanan as $p)
        @php
            $borderAccent = 'border-l-4 border-l-indigo-500';
            if ($p->status_pesanan === 'selesai') {
                $borderAccent = 'border-l-4 border-l-emerald-500';
            } elseif ($p->status_pesanan === 'disetujui') {
                $borderAccent = 'border-l-4 border-l-brand-500';
            } elseif ($p->status_pesanan === 'menunggu') {
                $borderAccent = 'border-l-4 border-l-amber-500';
            } elseif (in_array($p->status_pesanan, ['dibatalkan', 'ditolak'])) {
                $borderAccent = 'border-l-4 border-l-red-500';
            }
        @endphp
        
        <div class="block group relative">
            <x-card class="transition-all duration-350 hover:shadow-lg border border-gray-100 hover:border-brand-200 {{ $borderAccent }} p-6 sm:p-7 relative overflow-hidden bg-white rounded-[2rem]">
                <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    
                    <!-- Date Badge (Interactive Link) -->
                    <a href="{{ route('pelanggan.pesanan.show', $p->id_pesanan) }}" class="bg-brand-50/60 rounded-2xl p-3.5 text-center min-w-[95px] border border-brand-100/50 hover:bg-brand-100/80 transition-all flex-shrink-0 flex flex-col justify-center select-none">
                        <span class="block text-[10px] font-black text-brand-650 uppercase tracking-widest">{{ \Carbon\Carbon::parse($p->tanggal_booking)->translatedFormat('M') }}</span>
                        <span class="block text-2xl font-black text-brand-700 mt-0.5 leading-none">{{ \Carbon\Carbon::parse($p->tanggal_booking)->format('d') }}</span>
                        <span class="block text-[10px] font-extrabold text-brand-500/85 mt-1.5 bg-white/70 px-1.5 py-0.5 rounded-md border border-brand-100/30">{{ \Carbon\Carbon::parse($p->jam_mulai)->format('H:i') }}</span>
                    </a>
                    
                    <!-- Core Details -->
                    <a href="{{ route('pelanggan.pesanan.show', $p->id_pesanan) }}" class="flex-grow min-w-0">
                        <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
                            <h3 class="text-base sm:text-lg font-black text-gray-900 group-hover:text-brand-600 transition-colors truncate pr-2 leading-snug">{{ $p->jasa->nama_jasa }}</h3>
                            <x-badge :variant="$p->status_variant" class="font-extrabold px-3 py-1 text-[10px] uppercase tracking-wider">{{ $p->status_label }}</x-badge>
                        </div>
                        
                        <p class="text-xs font-bold text-gray-400 flex items-center gap-2 mb-4 bg-gray-50/50 p-2.5 rounded-xl border border-gray-100/50 w-fit">
                            <img src="{{ $p->jasa->penyedia->foto_profil_url }}" class="w-5 h-5 rounded-full object-cover border border-gray-200" alt="">
                            <span class="text-gray-600 font-extrabold">{{ $p->jasa->penyedia->nama_lengkap }}</span>
                        </p>
                        
                        <!-- Metadata Row with Sharp vector SVGs -->
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-xs font-semibold text-gray-500 border-t border-gray-100 pt-3.5">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16"/></svg>
                                <span class="text-gray-400">ID:</span> <span class="font-extrabold text-gray-700">#{{ str_pad($p->id_pesanan, 5, '0', STR_PAD_LEFT) }}</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="font-extrabold text-gray-700">{{ abs(\Carbon\Carbon::parse($p->jam_selesai)->diffInHours(\Carbon\Carbon::parse($p->jam_mulai))) }} Jam</span>
                            </span>
                            <span class="flex items-center gap-1.5 text-brand-600">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="font-extrabold text-brand-700">{{ $p->jasa->tarif_label }}</span>
                            </span>
                        </div>
                    </a>
                    
                    <!-- Actions (Delete and Chevron Detail) -->
                    <div class="flex items-center gap-3.5 self-end sm:self-center ml-auto flex-shrink-0">
                        <form action="{{ route('pelanggan.pesanan.destroy', $p->id_pesanan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini dari riwayat?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-2xl hover:scale-105 transition-all shadow-sm flex items-center justify-center border border-red-100/50" title="Hapus Riwayat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        
                        <a href="{{ route('pelanggan.pesanan.show', $p->id_pesanan) }}" class="hidden sm:flex p-3 text-gray-300 group-hover:text-brand-500 group-hover:bg-brand-50 group-hover:translate-x-1 rounded-2xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </x-card>
        </div>
    @empty
        <div class="text-center py-20 bg-white rounded-[2.5rem] shadow-[0_5px_20px_rgba(0,0,0,0.015)] border border-gray-100 p-8 max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto text-gray-400 mb-6 border border-gray-100 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 tracking-tight">Tidak ada riwayat ditemukan</h3>
            <p class="text-sm font-semibold text-gray-400 mt-2 max-w-md mx-auto leading-relaxed">Belum ada pesanan dengan status terpilih atau riwayat telah dibersihkan.</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $pesanan->links('components.pagination') }}
</div>

@endsection
