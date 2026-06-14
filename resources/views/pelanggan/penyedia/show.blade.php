@extends('layouts.app')
@section('title', 'Profil Penyedia - ' . $penyedia->nama_lengkap)

@section('content')

<div class="mb-5">
    <a href="{{ route('pelanggan.cari') }}"
       class="inline-flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Pencarian
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Sidebar: Profil --}}
    <div class="lg:col-span-1">
        <div class="sticky top-24 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] overflow-hidden transition-all duration-300 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)]">
                {{-- Cover gradient --}}
                <div class="h-24 bg-gradient-to-r from-indigo-500 to-blue-600 relative">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15),transparent_60%)]"></div>
                </div>

                <div class="px-6 pb-6 -mt-12 text-center relative z-10">
                    <div class="w-20 h-20 mx-auto mb-3 rounded-2xl overflow-hidden border-4 border-white shadow-lg">
                        <img src="{{ $penyedia->foto_profil_url }}" alt="{{ $penyedia->nama_lengkap }}" class="w-full h-full object-cover">
                    </div>

                <h1 class="text-xl font-black text-gray-900 tracking-tight mb-0.5">{{ $penyedia->nama_lengkap }}</h1>
                <p class="text-gray-400 font-semibold text-xs">{{ $penyedia->email }}</p>

                {{-- Verified badge --}}
                @if($penyedia->status_verifikasi === 'diverifikasi')
                    <div class="mt-2 inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-200/60">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Penyedia Terverifikasi
                    </div>
                @endif

                {{-- Rating --}}
                <div class="mt-4 flex items-center justify-center gap-1.5">
                    <div class="flex gap-0.5">
                        @for($i=1; $i<=5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($penyedia->rating_rata_rata) ? 'text-amber-400' : 'text-gray-200' }} fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-base font-black text-gray-800">{{ number_format($penyedia->rating_rata_rata ?? 0, 1) }}</span>
                    <span class="text-xs font-semibold text-gray-400">({{ $ulasan->count() }} ulasan)</span>
                </div>
            </div>

            {{-- Report link --}}
            <div class="px-6 pb-3">
                <a href="{{ route('pelanggan.laporan.create', $penyedia->id_pengguna) }}"
                   class="inline-flex items-center gap-1.5 text-[10px] font-bold text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Laporkan Penyedia Ini
                </a>
            </div>

            {{-- Contact Info --}}
            <div class="px-6 pb-6 space-y-3 border-t border-gray-100 pt-4">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Kontak & Lokasi</h4>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-indigo-100">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 pt-1">{{ $penyedia->no_telepon }}</span>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-rose-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-rose-100 mt-0.5">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 leading-relaxed">{{ $penyedia->alamat }}</span>
                </div>
            </div>
        </div>

        {{-- Portofolio --}}
        @if($penyedia->portofolio->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
                <h3 class="text-sm font-black text-gray-900 mb-4 uppercase tracking-wider">Portofolio Kerja</h3>
                <div class="grid grid-cols-2 gap-2.5">
                    @foreach($penyedia->portofolio->take(4) as $p)
                        <div class="group relative rounded-xl overflow-hidden h-24 cursor-pointer border border-gray-100 shadow-sm"
                             x-data="" x-on:click="$dispatch('open-modal', 'porto-{{ $p->id_portofolio }}')">
                            <img src="{{ Storage::disk('public')->url($p->foto_proyek) }}"
                                 onerror="this.onerror=null;this.src='/images/placeholder.svg';"
                                 alt="{{ $p->judul_proyek }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-black">Lihat</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        </div>
    </div>

    {{-- Main: Layanan & Ulasan --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- Daftar Layanan --}}
        <div>
            <h2 class="text-xl font-black text-gray-900 mb-5 flex items-center gap-2">
                Layanan yang Ditawarkan
                <span class="text-sm font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $penyedia->jasaSebagaiPenyedia->count() }}</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @forelse($penyedia->jasaSebagaiPenyedia as $i => $jasa)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group"
                         style="animation: fadeInUp 0.4s {{ $i * 0.08 }}s cubic-bezier(0.16,1,0.3,1) both;">
                        <div class="relative h-40 overflow-hidden flex-shrink-0">
                            <img src="{{ $jasa->foto_jasa_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                            <div class="absolute top-3 left-3">
                                <x-badge variant="info" class="bg-white/90 backdrop-blur-sm text-indigo-700 font-bold">{{ $jasa->kategori_jasa }}</x-badge>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-base font-black text-gray-900 mb-1.5 group-hover:text-indigo-700 transition-colors">{{ $jasa->nama_jasa }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-4 flex-grow leading-relaxed">{{ $jasa->deskripsi_jasa }}</p>
                            <div class="flex items-center justify-between pt-3.5 border-t border-gray-100">
                                <div>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">
                                        {{ $jasa->tipe_tarif === 'paket' ? 'Mulai Dari' : 'Tarif' }}
                                    </span>
                                    <span class="text-indigo-600 font-black text-base">{{ $jasa->tarif_label }}</span>
                                </div>
                                <a href="{{ route('pelanggan.booking.create', $jasa->id_jasa) }}"
                                   class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-black transition-all shadow-sm shadow-indigo-200 active:scale-95">
                                    Pesan
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <p class="text-sm font-semibold text-gray-500">Penyedia ini belum memiliki layanan aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Ulasan --}}
        <div>
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                    Ulasan Pelanggan
                    <span class="text-sm font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $ulasan->count() }}</span>
                </h2>
            </div>

            @if($ulasan->count() > 0)
                {{-- Rating breakdown --}}
                @php
                    $avgRating = $penyedia->rating_rata_rata;
                    $ratingCounts = collect($ulasan)->groupBy(fn($p) => (int)$p->ulasan->rating)->map->count();
                @endphp
                <div class="bg-indigo-50 rounded-2xl p-5 border border-indigo-100 mb-6 flex items-center gap-6">
                    <div class="text-center flex-shrink-0">
                        <p class="text-5xl font-black text-indigo-900">{{ $avgRating }}</p>
                        <div class="flex justify-center gap-0.5 mt-1">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-indigo-200' }} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs font-semibold text-indigo-500 mt-1">dari {{ $ulasan->count() }} ulasan</p>
                    </div>
                    <div class="flex-1 space-y-1.5">
                        @for($star=5; $star>=1; $star--)
                            @php $count = $ratingCounts->get($star, 0); $pct = $ulasan->count() > 0 ? ($count / $ulasan->count()) * 100 : 0; @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-indigo-500 w-3">{{ $star }}</span>
                                <div class="flex-1 h-1.5 bg-indigo-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full transition-all duration-700" style="width: {{ $pct }}%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-indigo-400 w-4 text-right">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="space-y-4">
                    @php
                        $showLimit = $ulasan->count() > 3;
                        $displayUlasan = $showLimit ? $ulasan->take(3) : $ulasan;
                    @endphp
                    @foreach($displayUlasan as $i => $pesanan)
                        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)]"
                             style="animation: fadeInUp 0.4s {{ $i * 0.07 }}s cubic-bezier(0.16,1,0.3,1) both;">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $pesanan->pelanggan->foto_profil_url }}" class="w-9 h-9 rounded-xl object-cover" alt="">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm">{{ $pesanan->pelanggan->nama_lengkap }}</h4>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $pesanan->jasa->nama_jasa }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($pesanan->ulasan->tanggal_ulasan)->diffForHumans() }}</span>
                            </div>
                            <x-star-rating :rating="$pesanan->ulasan->rating" readonly="true" />
                            <p class="text-gray-700 text-sm mt-2 leading-relaxed">"{{ $pesanan->ulasan->komentar_ulasan }}"</p>
                        </div>
                    @endforeach

                    @if($showLimit)
                        <div class="mt-6 text-center">
                            <a href="{{ route('pelanggan.penyedia.ulasan', $penyedia->id_pengguna) }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-sm rounded-2xl border border-indigo-100 hover:border-indigo-200 transition-all shadow-sm hover:scale-[1.02] active:scale-95 duration-200">
                                Lihat Semua Ulasan ({{ $ulasan->count() }})
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <div class="w-14 h-14 bg-white rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-3 shadow-sm">
                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-600">Belum ada ulasan</p>
                    <p class="text-xs text-gray-400 font-medium mt-1">Jadilah yang pertama memberikan ulasan untuk penyedia ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

{{-- =====================================================
     Portfolio Modals — pushed OUTSIDE all section content
     to body level to escape any CSS stacking context
     caused by sticky/transform/filter on ancestor elements.
     This ensures modals work regardless of scroll position.
     ===================================================== --}}
@push('modals')
    @foreach($penyedia->portofolio->take(4) as $p)
        <x-modal name="porto-{{ $p->id_portofolio }}" maxWidth="2xl">
            <div class="overflow-hidden rounded-3xl shadow-2xl">
                {{-- Image Showcase --}}
                <div class="relative bg-gray-950 overflow-hidden" style="height: 22rem;">
                    <img src="{{ Storage::disk('public')->url($p->foto_proyek) }}"
                         alt="{{ $p->judul_proyek }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent pointer-events-none"></div>

                    {{-- Title overlaid on image bottom --}}
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-600/80 backdrop-blur-md text-white text-[10px] font-black rounded-lg uppercase tracking-wider mb-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Karya & Portofolio
                        </span>
                        <h3 class="text-2xl font-black text-white leading-tight drop-shadow-lg">{{ $p->judul_proyek }}</h3>
                    </div>
                </div>

                {{-- Detail Content --}}
                <div class="bg-white p-6 space-y-4">
                    {{-- Description Card --}}
                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-5 h-5 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Deskripsi Hasil Kerja</span>
                        </div>
                        @if($p->deskripsi_proyek)
                            <p class="text-gray-700 text-sm font-medium leading-relaxed whitespace-pre-line">{{ $p->deskripsi_proyek }}</p>
                        @else
                            <p class="text-gray-400 text-xs font-medium italic">Penyedia belum menambahkan deskripsi untuk karya ini.</p>
                        @endif
                    </div>

                    {{-- Footer Row --}}
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-xs text-gray-500 font-bold">Pekerjaan Selesai</span>
                        </div>
                        <button x-on:click="$dispatch('close')"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-black rounded-xl hover:scale-[1.02] active:scale-95 transition-all duration-200">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </x-modal>
    @endforeach
@endpush
