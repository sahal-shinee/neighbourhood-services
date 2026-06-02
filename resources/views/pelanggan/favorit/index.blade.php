@extends('layouts.app')
@section('title', 'Jasa Favorit Saya')
@section('header', 'Jasa Favorit')
@section('subheader', 'Jasa yang Anda simpan untuk mudah ditemukan kembali.')

@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

@if($favorit->total() === 0)
    <div class="text-center py-20 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm max-w-lg mx-auto p-10">
        <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-rose-100">
            <svg class="w-9 h-9 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-black text-gray-900">Belum Ada Favorit</h3>
        <p class="text-sm font-semibold text-gray-400 mt-2 leading-relaxed max-w-xs mx-auto">
            Tekan ikon hati pada kartu jasa untuk menyimpannya ke daftar favorit Anda.
        </p>
        <a href="{{ route('pelanggan.cari') }}"
           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-brand-50 text-brand-600 border border-brand-100 rounded-xl font-bold text-sm hover:bg-brand-600 hover:text-white transition-all">
            Cari Jasa Sekarang
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($favorit as $f)
            @if($f->jasa)
                <div class="relative">
                    {{-- Tombol hapus favorit --}}
                    <form method="POST" action="{{ route('pelanggan.favorit.toggle', $f->jasa->id_jasa) }}"
                          class="absolute top-3 right-3 z-10"
                          onsubmit="return confirm('Hapus dari favorit?')">
                        @csrf
                        <button type="submit"
                            class="w-8 h-8 bg-white/90 hover:bg-red-50 border border-red-100 rounded-full flex items-center justify-center shadow-sm transition-all hover:scale-110"
                            title="Hapus dari favorit">
                            <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>
                    <x-service-card :jasa="$f->jasa" />
                </div>
            @endif
        @endforeach
    </div>

    @if($favorit->hasPages())
    <div class="mt-10">
        {{ $favorit->links('components.pagination') }}
    </div>
    @endif
@endif

@endsection
