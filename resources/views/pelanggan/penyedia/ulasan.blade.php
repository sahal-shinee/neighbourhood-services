@extends('layouts.app')
@section('title', 'Semua Ulasan - ' . $penyedia->nama_lengkap)
@section('header', 'Semua Ulasan Pelanggan')
@section('subheader', 'Lihat seluruh umpan balik dan penilaian transparan untuk penyedia jasa ini.')

@section('content')

<div class="mb-5">
    <a href="{{ route('pelanggan.penyedia.show', $penyedia->id_pengguna) }}"
       class="inline-flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Profil Penyedia
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Sidebar: Ringkasan Penyedia --}}
    <div class="lg:col-span-1">
        <div class="sticky top-24 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden">
                <div class="h-20 bg-gradient-to-r from-indigo-500 to-blue-600 relative">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15),transparent_60%)]"></div>
                </div>

                <div class="px-5 pb-5 -mt-10 text-center relative z-10">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-white">
                        <img src="{{ $penyedia->foto_profil_url }}" alt="{{ $penyedia->nama_lengkap }}" class="w-full h-full object-cover">
                    </div>

                    <h3 class="text-base font-black text-gray-900 leading-tight mb-0.5">{{ $penyedia->nama_lengkap }}</h3>
                    <p class="text-gray-400 font-semibold text-[10px] mb-3">{{ $penyedia->email }}</p>

                    @if($penyedia->status_verifikasi === 'diverifikasi')
                        <div class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[9px] font-black px-2.5 py-0.5 rounded-full border border-emerald-200/50">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Terverifikasi
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-center gap-2">
                        <span class="text-3xl font-black text-gray-900">{{ number_format($penyedia->rating_rata_rata ?? 0, 1) }}</span>
                        <div class="text-left">
                            <div class="flex gap-0.5">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= round($penyedia->rating_rata_rata) ? 'text-amber-400' : 'text-gray-200' }} fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 mt-0.5">Total {{ $ulasan->total() }} Ulasan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Column: Daftar Ulasan Lengkap --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] p-6">
            <h2 class="text-lg font-black text-gray-900 mb-6 flex items-center justify-between">
                <span>Daftar Ulasan</span>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full">Halaman {{ $ulasan->currentPage() }} dari {{ $ulasan->lastPage() }}</span>
            </h2>

            <div class="space-y-4">
                @forelse($ulasan as $i => $pesanan)
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-indigo-100 hover:shadow-[0_4px_12px_rgba(99,102,241,0.04)] transition-all duration-300"
                         style="animation: fadeInUp 0.4s {{ $i * 0.07 }}s cubic-bezier(0.16,1,0.3,1) both;">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $pesanan->pelanggan->foto_profil_url }}" class="w-9 h-9 rounded-xl object-cover border border-gray-100 shadow-sm" alt="">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $pesanan->pelanggan->nama_lengkap }}</h4>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $pesanan->jasa->nama_jasa }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($pesanan->ulasan->tanggal_ulasan)->diffForHumans() }}</span>
                        </div>
                        <div class="mb-2">
                            <x-star-rating :rating="$pesanan->ulasan->rating" readonly="true" />
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed">"{{ $pesanan->ulasan->komentar_ulasan }}"</p>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <span class="text-4xl block mb-4">💬</span>
                        <p class="text-gray-500 font-semibold text-sm">Belum ada ulasan untuk penyedia ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            @if($ulasan->hasPages())
                <div class="mt-8 pt-6 border-t border-gray-50">
                    {{ $ulasan->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
