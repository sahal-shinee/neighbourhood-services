@extends('layouts.app')
@section('title', 'Layanan Saya')
@section('header', 'Kelola Layanan Jasa')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-sm font-medium text-gray-500">Daftar layanan yang Anda tawarkan ke pelanggan.</p>
    <a href="{{ route('penyedia.jasa.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm shadow-indigo-200 transition-all hover:-translate-y-0.5 active:scale-[0.97]">
        <svg class="w-4 h-4 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Layanan
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($jasa as $i => $j)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-[0_12px_35px_rgba(0,0,0,0.08)] hover:-translate-y-1 {{ !$j->is_aktif ? 'opacity-60' : '' }}"
             style="animation: fadeInUp 0.4s {{ $i * 0.07 }}s cubic-bezier(0.16,1,0.3,1) both;">

            {{-- Image --}}
            <div class="relative h-44 overflow-hidden flex-shrink-0">
                <img src="{{ $j->foto_jasa_url }}" alt="{{ $j->nama_jasa }}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 {{ !$j->is_aktif ? 'grayscale' : '' }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

                @if(!$j->is_aktif)
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 backdrop-blur-[2px]">
                        <span class="bg-rose-500 text-white px-3 py-1 rounded-full text-xs font-black tracking-wider">NONAKTIF</span>
                    </div>
                @endif

                <div class="absolute top-3 left-3">
                    <span class="bg-white/90 backdrop-blur-sm text-indigo-700 text-[10px] font-black px-2.5 py-1 rounded-lg shadow-sm">
                        {{ $j->kategori_jasa }}
                    </span>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-5 flex flex-col flex-grow">
                <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1">{{ $j->nama_jasa }}</h3>
                <p class="text-xs text-gray-500 mb-4 line-clamp-2 flex-grow leading-relaxed">{{ $j->deskripsi_jasa }}</p>

                <div class="flex justify-between items-center mb-4 pb-4 border-t border-gray-100 pt-3">
                    <div>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">
                            @if($j->tipe_tarif === 'per_jam') Tarif/Jam
                            @elseif($j->tipe_tarif === 'per_pengerjaan') Tarif/Pengerjaan
                            @else Paket
                            @endif
                        </span>
                        <span class="text-indigo-600 font-black text-base">{{ $j->tarif_label }}</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    {{-- Edit Button --}}
                    <a href="{{ route('penyedia.jasa.edit', $j->id_jasa) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 bg-gray-50 hover:bg-gray-100 border border-gray-200/60 text-gray-700 text-xs font-black px-3 py-2.5 rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>

                    {{-- Toggle Status Button --}}
                    <form action="{{ route('penyedia.jasa.toggle-status', $j->id_jasa) }}" method="POST" class="flex-1"
                          x-data @submit.prevent="if(confirm('{{ $j->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }} layanan ini?')) $el.submit()">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 text-xs font-black px-3 py-2.5 rounded-xl border transition-all {{ $j->is_aktif ? 'bg-amber-50 hover:bg-amber-100 text-amber-700 border-amber-100' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-100' }}">
                            @if($j->is_aktif)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                Nonaktifkan
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Aktifkan
                            @endif
                        </button>
                    </form>

                    {{-- Delete Button --}}
                    <form action="{{ route('penyedia.jasa.destroy', $j->id_jasa) }}" method="POST" class="flex-shrink-0"
                          x-data @submit.prevent="if(confirm('Hapus layanan ini secara permanen?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button type="submit" title="Hapus Layanan"
                            class="w-10 h-10 inline-flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl border border-rose-100 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-20 h-20 mx-auto bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mb-5"
                     style="animation: float-slow 5s ease-in-out infinite;">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">Belum ada layanan</h3>
                <p class="text-sm text-gray-400 font-medium mt-1.5 max-w-xs mx-auto">Tambahkan layanan keahlian Anda untuk mulai menerima pesanan dari pelanggan.</p>
                <a href="{{ route('penyedia.jasa.create') }}"
                   class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Layanan Pertama
                </a>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $jasa->links('components.pagination') }}
</div>

@push('styles')
<style>
    @keyframes float-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush

@endsection
