@extends('layouts.app')
@section('title', 'Laporan Saya')
@section('header', 'Laporan Saya')
@section('subheader', 'Pantau status laporan yang telah Anda kirimkan kepada admin.')

@section('content')

{{-- Flash message --}}
@if(session('success'))
<div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

@if($laporan->total() === 0)
    {{-- Empty state --}}
    <div class="text-center py-20 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm max-w-lg mx-auto p-10">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-200">
            <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
            </svg>
        </div>
        <h3 class="text-lg font-black text-gray-900">Belum Ada Laporan</h3>
        <p class="text-sm font-semibold text-gray-400 mt-2 leading-relaxed max-w-xs mx-auto">
            Anda belum pernah mengirimkan laporan. Gunakan fitur laporan jika menemukan pelanggaran dari penyedia jasa.
        </p>
        <a href="{{ route('pelanggan.cari') }}"
           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-brand-50 text-brand-600 border border-brand-100 rounded-xl font-bold text-sm hover:bg-brand-600 hover:text-white transition-all">
            Cari Jasa
        </a>
    </div>
@else

    {{-- Info banner --}}
    <div class="mb-6 flex items-start gap-3 px-5 py-4 bg-blue-50 border border-blue-100 rounded-2xl text-sm text-blue-700">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-semibold leading-relaxed">
            Laporan yang sudah berstatus <span class="font-black">Ditindaklanjuti</span> atau <span class="font-black">Ditolak</span>
            akan otomatis dihapus dari sistem setelah <span class="font-black">30 hari</span>.
        </p>
    </div>

    {{-- Report cards --}}
    <div class="space-y-4">
        @foreach($laporan as $l)
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                {{-- Penyedia avatar --}}
                <div class="flex-shrink-0">
                    <img src="{{ $l->penyedia->foto_profil_url }}"
                         alt="{{ $l->penyedia->nama_lengkap }}"
                         class="w-12 h-12 rounded-2xl object-cover border border-gray-100">
                </div>

                {{-- Main content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-0.5">Laporan #{{ $l->id_laporan }}</p>
                            <h3 class="text-base font-black text-gray-900">{{ $l->penyedia->nama_lengkap }}</h3>
                        </div>
                        <span class="self-start sm:self-center px-3 py-1.5 text-[10px] font-black rounded-full border {{ $l->statusColor() }}">
                            {{ $l->statusLabel() }}
                        </span>
                    </div>

                    {{-- Reason & date --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mb-3">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 border border-red-100 px-2.5 py-1 rounded-xl">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ $l->alasan }}
                        </span>
                        <span class="text-xs text-gray-400 font-semibold">
                            {{ $l->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    {{-- Detail snippet --}}
                    <p class="text-sm text-gray-500 font-medium leading-relaxed line-clamp-2 mb-3">
                        {{ $l->detail_laporan }}
                    </p>

                    {{-- Status timeline / admin note --}}
                    <div class="border-t border-gray-100 pt-3 mt-1">
                        @if($l->catatan_admin)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wide mb-0.5">Catatan Admin</p>
                                    <p class="text-xs font-semibold text-gray-700">{{ $l->catatan_admin }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                @if($l->status === 'baru')
                                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                                    Laporan sedang menunggu ditinjau oleh admin.
                                @elseif($l->status === 'ditinjau')
                                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                    Admin sedang meninjau laporan Anda.
                                @elseif($l->status === 'ditindaklanjuti')
                                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                    Laporan telah ditindaklanjuti oleh admin.
                                @elseif($l->status === 'ditolak')
                                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                    Laporan tidak memenuhi syarat dan telah ditolak.
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($laporan->hasPages())
    <div class="mt-8">
        {{ $laporan->links('components.pagination') }}
    </div>
    @endif

@endif

@endsection
