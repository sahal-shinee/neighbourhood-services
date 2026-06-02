@extends('layouts.app')
@section('title', 'Verifikasi Penyedia Jasa')
@section('header', 'Verifikasi Penyedia Jasa')
@section('subheader', 'Tinjau dan putuskan kelayakan pendaftaran penyedia jasa berdasarkan data KTP.')

@section('content')

@php
    $hasBanding = $penyedia_pending->filter(fn($p) => !empty($p->pesan_banding))->count();
@endphp

@if($hasBanding > 0)
<div class="mb-6 flex items-start gap-3 bg-amber-50 border border-amber-200 px-5 py-4 rounded-2xl">
    <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-amber-200">
        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    </div>
    <div>
        <p class="font-bold text-amber-900 text-sm">{{ $hasBanding }} Penyedia Mengajukan Banding</p>
        <p class="text-xs text-amber-700 font-medium mt-0.5">Terdapat penyedia yang sebelumnya ditolak dan kini telah mengirimkan pesan banding disertai alasan dan/atau foto KTP baru. Harap ditinjau dengan seksama.</p>
    </div>
</div>
@endif

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Table Header --}}
    <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-gray-900">Daftar Menunggu Verifikasi</h3>
            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $penyedia_pending->count() }} penyedia pending</p>
        </div>
        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-full">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50/80">
                    <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Penyedia</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Kontak</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Terdaftar</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status & KTP</th>
                    <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($penyedia_pending as $penyedia)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        {{-- Identity --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-blue-100 flex items-center justify-center text-sm font-extrabold text-indigo-600 flex-shrink-0 uppercase">
                                    {{ strtoupper(substr($penyedia->nama_lengkap, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $penyedia->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5 max-w-[180px] truncate" title="{{ $penyedia->alamat }}">{{ $penyedia->alamat }}</p>
                                    @if(!empty($penyedia->pesan_banding))
                                        <span class="inline-flex items-center gap-1 mt-1.5 text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            AJU BANDING
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Kontak --}}
                        <td class="px-6 py-5">
                            <p class="text-sm font-semibold text-gray-800">{{ $penyedia->no_telepon }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $penyedia->email }}</p>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-700">{{ $penyedia->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $penyedia->created_at->format('H:i') }} WIB</p>
                        </td>

                        {{-- KTP & Banding --}}
                        <td class="px-6 py-5">
                            @if($penyedia->foto_ktp)
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'ktp-{{ $penyedia->id_pengguna }}')"
                                    class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-3 py-1.5 rounded-xl transition-all hover:scale-105">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat KTP
                                </button>

                                {{-- KTP Modal --}}
                                <x-modal name="ktp-{{ $penyedia->id_pengguna }}" maxWidth="2xl">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-5">
                                            <div>
                                                <h2 class="text-lg font-bold text-gray-900">Foto KTP — {{ $penyedia->nama_lengkap }}</h2>
                                                <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $penyedia->email }}</p>
                                            </div>
                                            @if(!empty($penyedia->pesan_banding))
                                                <span class="text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-full uppercase tracking-wider">Aju Banding</span>
                                            @endif
                                        </div>

                                        {{-- Appeal message display --}}
                                        @if(!empty($penyedia->pesan_banding))
                                            <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                                                <p class="text-xs font-bold text-amber-800 mb-2 flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3z"/></svg>
                                                    Pesan Aju Banding dari Penyedia:
                                                </p>
                                                <p class="text-sm text-amber-900 font-medium leading-relaxed">{{ $penyedia->pesan_banding }}</p>
                                            </div>
                                        @endif

                                        <div class="bg-gray-100 rounded-2xl overflow-hidden flex items-center justify-center p-3">
                                            <img src="{{ route('admin.pengguna.ktp', $penyedia->id_pengguna) }}" alt="KTP {{ $penyedia->nama_lengkap }}" class="max-w-full h-auto max-h-[60vh] object-contain rounded-lg">
                                        </div>

                                        <div class="mt-6 flex items-center justify-between">
                                            <div class="flex gap-3">
                                                <form action="{{ route('admin.verifikasi.approve', $penyedia->id_pengguna) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold px-5 py-2.5 rounded-xl shadow-sm shadow-emerald-200 transition-all hover:scale-105 text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.verifikasi.reject', $penyedia->id_pengguna) }}" method="POST" onsubmit="return confirm('Tolak penyedia {{ $penyedia->nama_lengkap }}?');">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-bold px-5 py-2.5 rounded-xl transition-all hover:scale-105 text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                            <x-button variant="secondary" x-on:click="$dispatch('close')">Tutup</x-button>
                                        </div>
                                    </div>
                                </x-modal>
                            @else
                                <span class="text-xs font-semibold text-rose-400 bg-rose-50 border border-rose-100 px-3 py-1 rounded-xl">Tidak ada KTP</span>
                            @endif
                        </td>

                        {{-- Action Buttons --}}
                        <td class="px-6 py-5">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.verifikasi.approve', $penyedia->id_pengguna) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-emerald-200 transition-all hover:scale-105">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.verifikasi.reject', $penyedia->id_pengguna) }}" method="POST" onsubmit="return confirm('Tolak penyedia ini?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-xs font-bold px-4 py-2 rounded-xl transition-all hover:scale-105">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-base font-bold text-gray-700">Semua Sudah Diverifikasi!</p>
                                <p class="text-sm text-gray-400 font-medium mt-1">Tidak ada penyedia jasa yang menunggu verifikasi saat ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
