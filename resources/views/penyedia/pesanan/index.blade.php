@extends('layouts.app')
@section('title', 'Pesanan Masuk')
@section('header', 'Kelola Pesanan')
@section('subheader', 'Tinjau, setujui, dan pantau seluruh pesanan yang masuk untuk layanan Anda.')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-sm font-semibold">
    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('warning'))
<div class="mb-5 flex items-center gap-3 p-4 bg-amber-50 border border-amber-100 rounded-2xl text-amber-800 text-sm font-semibold">
    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    {{ session('warning') }}
</div>
@endif

{{-- ── Toolbar: Filter Tanggal + Status Tabs ─────────────────────────────── --}}
<div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-5 mb-6">
    {{-- Date Filter Row --}}
    <form method="GET" action="{{ route('penyedia.pesanan.index') }}"
          class="flex flex-wrap items-end gap-3 mb-4 pb-4 border-b border-gray-100">
        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Dari Tanggal</label>
            <div class="relative">
                <input type="date" name="dari" value="{{ request('dari') }}"
                    class="bg-gray-50 border border-gray-200 text-gray-800 rounded-xl pl-3 pr-8 py-2.5 text-sm font-semibold focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}"
                class="bg-gray-50 border border-gray-200 text-gray-800 rounded-xl px-3 py-2.5 text-sm font-semibold focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
        </div>
        <button type="submit"
            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl transition-all shadow-sm shadow-indigo-200 hover:shadow-indigo-300 flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Terapkan
        </button>
        @if(request('dari') || request('sampai'))
            <a href="{{ route('penyedia.pesanan.index', request('status') ? ['status' => request('status')] : []) }}"
               class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset Filter
            </a>
        @endif
    </form>

    {{-- Status Tabs --}}
    @php
        $tabs = [
            ''           => ['label' => 'Semua',      'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
            'menunggu'   => ['label' => 'Menunggu',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'disetujui'  => ['label' => 'Disetujui',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            'selesai'    => ['label' => 'Selesai',    'icon' => 'M5 13l4 4L19 7'],
            'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        $currentStatus = request('status', '');
        $tabColors = [
            ''           => 'bg-gray-800 text-white shadow-sm shadow-gray-300',
            'menunggu'   => 'bg-amber-500 text-white shadow-sm shadow-amber-200',
            'disetujui'  => 'bg-blue-600 text-white shadow-sm shadow-blue-200',
            'selesai'    => 'bg-emerald-600 text-white shadow-sm shadow-emerald-200',
            'dibatalkan' => 'bg-rose-500 text-white shadow-sm shadow-rose-200',
        ];
        $tabInactive = 'text-gray-500 hover:text-gray-800 hover:bg-gray-100';
    @endphp
    <div class="flex flex-wrap items-center gap-2">
        @foreach($tabs as $val => $tab)
            <a href="{{ route('penyedia.pesanan.index', array_filter(['status' => $val ?: null, 'dari' => request('dari'), 'sampai' => request('sampai')])) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap
                      {{ $currentStatus === $val ? $tabColors[$val] : $tabInactive }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                </svg>
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>

{{-- ── Results Summary ─────────────────────────────────────────────────────── --}}
<div class="flex items-center justify-between mb-4">
    <p class="text-sm font-semibold text-gray-500">
        Menampilkan <span class="font-black text-gray-900">{{ $pesanan->firstItem() ?? 0 }}–{{ $pesanan->lastItem() ?? 0 }}</span>
        dari <span class="font-black text-gray-900">{{ $pesanan->total() }}</span> pesanan
    </p>
    @if(request('dari') || request('sampai') || request('status'))
        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-xl">
            Filter aktif
        </span>
    @endif
</div>

{{-- ── Order Cards ─────────────────────────────────────────────────────────── --}}
<div class="space-y-4">
    @forelse($pesanan as $i => $p)
    @php
        $statusConfig = match($p->status_pesanan) {
            'menunggu'   => ['bar' => 'bg-amber-400', 'badge_bg' => 'bg-amber-50', 'badge_text' => 'text-amber-700', 'badge_border' => 'border-amber-200', 'dot' => 'bg-amber-400'],
            'disetujui'  => ['bar' => 'bg-blue-500',  'badge_bg' => 'bg-blue-50',  'badge_text' => 'text-blue-700',  'badge_border' => 'border-blue-200',  'dot' => 'bg-blue-500'],
            'selesai'    => ['bar' => 'bg-emerald-500','badge_bg' => 'bg-emerald-50','badge_text' => 'text-emerald-700','badge_border' => 'border-emerald-200','dot' => 'bg-emerald-500'],
            'dibatalkan' => ['bar' => 'bg-rose-400',  'badge_bg' => 'bg-rose-50',  'badge_text' => 'text-rose-700',  'badge_border' => 'border-rose-200',  'dot' => 'bg-rose-400'],
            default      => ['bar' => 'bg-gray-300',  'badge_bg' => 'bg-gray-50',  'badge_text' => 'text-gray-600',  'badge_border' => 'border-gray-200',  'dot' => 'bg-gray-400'],
        };
    @endphp
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
        <div class="flex">
            {{-- Status accent bar --}}
            <div class="w-1 {{ $statusConfig['bar'] }} rounded-l-3xl flex-shrink-0"></div>

            <div class="flex-1 p-5 md:p-6">
                <div class="flex flex-col lg:flex-row gap-5">

                    {{-- ── Left: Info Jasa & Pelanggan ─────────────────────── --}}
                    <div class="flex-1 min-w-0">

                        {{-- Header row --}}
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-gray-400 font-mono tracking-wider">#{{ str_pad($p->id_pesanan, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                    <span class="text-[10px] font-bold text-gray-400">{{ $p->created_at->diffForHumans() }}</span>
                                </div>
                                <h3 class="text-base font-black text-gray-900 leading-snug">{{ $p->jasa->nama_jasa }}</h3>
                                <p class="text-sm font-bold text-indigo-600 mt-0.5">{{ $p->jasa->tarif_label }}</p>
                            </div>
                            {{-- Status Badge --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black rounded-xl border
                                {{ $statusConfig['badge_bg'] }} {{ $statusConfig['badge_text'] }} {{ $statusConfig['badge_border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }} {{ $p->status_pesanan === 'menunggu' ? 'animate-pulse' : '' }}"></span>
                                {{ $p->status_label }}
                            </span>
                        </div>

                        {{-- Pelanggan Card --}}
                        <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                            <div class="flex items-center gap-3 mb-3">
                                <img src="{{ $p->pelanggan->foto_profil_url }}" alt="{{ $p->pelanggan->nama_lengkap }}"
                                     class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-sm flex-shrink-0">
                                <div class="min-w-0">
                                    <p class="font-black text-gray-900 text-sm truncate">{{ $p->pelanggan->nama_lengkap }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span class="text-xs font-semibold text-gray-500">{{ $p->pelanggan->no_telepon }}</span>
                                    </div>
                                </div>
                                @if(in_array($p->status_pesanan, ['menunggu', 'disetujui']))
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p->pelanggan->no_telepon)) }}"
                                       target="_blank"
                                       class="ml-auto flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#25D366]/10 hover:bg-[#25D366] border border-[#25D366]/30 text-[#128C7E] hover:text-white text-xs font-black rounded-xl transition-all">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.418-.1.824z"/></svg>
                                        Chat
                                    </a>
                                @endif
                            </div>
                            {{-- Alamat --}}
                            <div class="flex items-start gap-1.5 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="font-medium leading-relaxed">{{ $p->pelanggan->alamat }}</span>
                            </div>
                            @if($p->catatan_tambahan)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Catatan Pelanggan</p>
                                <p class="text-xs font-medium text-gray-600 italic leading-relaxed">"{{ $p->catatan_tambahan }}"</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── Right: Jadwal & Aksi ──────────────────────────── --}}
                    <div class="lg:w-56 flex flex-col gap-3 flex-shrink-0">

                        {{-- Jadwal Card --}}
                        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-center">
                            <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-2">Jadwal Booking</p>
                            <p class="text-lg font-black text-indigo-900">{{ $p->tanggal_booking->translatedFormat('d M Y') }}</p>

                            @if($p->jasa->tipe_tarif === 'paket')
                                <p class="text-xs font-bold text-indigo-600 mt-1">{{ $p->paket ? $p->paket->nama_paket : 'Paket' }}</p>
                                <span class="inline-block mt-2 bg-indigo-100 text-indigo-700 text-[10px] font-black px-3 py-1 rounded-full">
                                    Estimasi {{ $p->estimasi_hari ?? '-' }} Hari
                                </span>
                                @if(in_array($p->status_pesanan, ['menunggu', 'disetujui']))
                                <form action="{{ route('penyedia.pesanan.estimasi', $p->id_pesanan) }}" method="POST" class="mt-3 flex items-center justify-center gap-1">
                                    @csrf @method('PATCH')
                                    <input type="number" name="estimasi_hari" value="{{ $p->estimasi_hari }}" min="1" max="365"
                                        class="w-14 rounded-lg border border-indigo-200 bg-white px-2 py-1 text-xs font-bold text-indigo-900 text-center focus:border-indigo-400 outline-none">
                                    <span class="text-[10px] text-indigo-400 font-semibold">Hari</span>
                                    <button type="submit" class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black rounded-lg transition-all">Edit</button>
                                </form>
                                @endif
                            @else
                                <p class="text-sm font-black text-indigo-700 mt-1.5">
                                    {{ \Carbon\Carbon::parse($p->jam_mulai)->format('H:i') }}
                                    <span class="font-normal text-indigo-400 mx-0.5">–</span>
                                    {{ \Carbon\Carbon::parse($p->jam_selesai)->format('H:i') }}
                                </p>
                                @php $jam = abs(\Carbon\Carbon::parse($p->jam_selesai)->diffInHours(\Carbon\Carbon::parse($p->jam_mulai))); @endphp
                                <span class="inline-block mt-1.5 bg-indigo-100 text-indigo-700 text-[10px] font-black px-3 py-1 rounded-full">
                                    {{ $p->jasa->tipe_tarif === 'per_jam' ? $jam . ' Jam' : '1 Pengerjaan' }}
                                </span>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        @if($p->status_pesanan === 'menunggu')
                        <div class="flex flex-col gap-2">
                            <form action="{{ route('penyedia.pesanan.setujui', $p->id_pesanan) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition-all shadow-sm shadow-emerald-200 hover:shadow-emerald-300 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Setujui Pesanan
                                </button>
                            </form>
                            <form action="{{ route('penyedia.pesanan.tolak', $p->id_pesanan) }}" method="POST"
                                  x-data @submit.prevent="if(confirm('Tolak pesanan dari {{ addslashes($p->pelanggan->nama_lengkap) }}?')) $el.submit()">
                                @csrf
                                <button type="submit"
                                    class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-xs font-black rounded-xl transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                            </form>
                        </div>
                        @elseif($p->status_pesanan === 'disetujui')
                        <form action="{{ route('penyedia.pesanan.selesai', $p->id_pesanan) }}" method="POST"
                              x-data @submit.prevent="if(confirm('Tandai pesanan ini selesai? Pastikan pembayaran sudah diterima.')) $el.submit()">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl transition-all shadow-sm shadow-indigo-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pekerjaan Selesai
                            </button>
                        </form>
                        @elseif($p->status_pesanan === 'selesai')
                        <div class="flex items-center justify-center gap-2 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Sudah Selesai
                        </div>
                        @elseif($p->status_pesanan === 'dibatalkan')
                        <div class="flex items-center justify-center gap-2 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-xs font-black rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Dibatalkan
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    {{-- Empty State --}}
    <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
        <div class="w-20 h-20 mx-auto bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-5">
            <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
        <h3 class="text-base font-black text-gray-800 mb-1">Tidak Ada Pesanan</h3>
        <p class="text-sm text-gray-400 font-medium max-w-xs mx-auto">Belum ada pesanan dengan filter yang dipilih saat ini.</p>
        @if(request('status') || request('dari') || request('sampai'))
        <a href="{{ route('penyedia.pesanan.index') }}"
           class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-sm rounded-xl transition-all">
            Reset Semua Filter
        </a>
        @endif
    </div>
    @endforelse
</div>

{{-- ── Pagination ───────────────────────────────────────────────────────────── --}}
@if($pesanan->hasPages())
<div class="mt-8">
    {{ $pesanan->links('components.pagination') }}
</div>
@endif

@endsection
