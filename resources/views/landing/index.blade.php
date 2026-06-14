@extends('layouts.landing')

@section('content')

<style>
    /* ─── Keyframes ─────────────────────────────────────────── */
    @keyframes marqueeLoop {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    @keyframes floatY {
        0%, 100% { transform: translateY(0px); }
        50%       { transform: translateY(-12px); }
    }
    @keyframes pulse-ring {
        0%        { transform: scale(1);   opacity: .6; }
        80%, 100% { transform: scale(2.2); opacity: 0;  }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    @keyframes shimmer {
        0%   { background-position: -400px 0; }
        100% { background-position:  400px 0; }
    }
    @keyframes ticker {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .animate-marquee  { display:flex; width:max-content; animation: marqueeLoop 30s linear infinite; }
    .marquee-wrap     { overflow:hidden; display:flex; position:relative; }
    .float-slow       { animation: floatY 6s ease-in-out infinite; }
    .pulse-ring::before {
        content: '';
        position: absolute; inset: 0;
        border-radius: 9999px;
        border: 2px solid currentColor;
        animation: pulse-ring 2.5s cubic-bezier(.215,.61,.355,1) infinite;
    }
    .section-fade { opacity:0; transform:translateY(32px); transition: opacity .7s ease, transform .7s ease; }
    .section-fade.visible { opacity:1; transform:none; }

    /* card tilt on hover */
    .tilt-card { transition: transform .35s ease, box-shadow .35s ease; transform-style: preserve-3d; }
    .tilt-card:hover { transform: perspective(700px) rotateX(-3deg) rotateY(4deg) translateY(-6px); }
</style>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 1. HERO                                                      --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="relative bg-white pt-24 pb-20 lg:pt-36 lg:pb-28 overflow-hidden"
         x-data="{ activeWorker: 1 }">

    {{-- Dot-grid background --}}
    <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:28px_28px] opacity-50 pointer-events-none"></div>
    {{-- Soft gradient orbs --}}
    <div class="absolute -top-32 -right-32 w-[520px] h-[520px] bg-indigo-100 rounded-full filter blur-[100px] opacity-50 pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] bg-blue-100  rounded-full filter blur-[80px]  opacity-40 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            {{-- LEFT: Content --}}
            <div class="lg:col-span-7" style="animation: fadeInUp .7s ease both;">

                {{-- Eyebrow badge --}}
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-brand-50 border border-brand-100 text-brand-700 font-bold text-xs tracking-widest uppercase mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
                    </span>
                    Platform Jasa Hiper-Lokal
                </div>

                {{-- Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-[3.6rem] font-black text-gray-900 tracking-tight leading-[1.08] mb-6">
                    Temukan Jasa Handal<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-600">Terdekat di Sekitar Anda</span>
                </h1>

                {{-- Subtitle --}}
                <p class="text-base sm:text-lg text-gray-600 mb-8 font-medium leading-relaxed">
                    Solusi praktis, instan, dan aman untuk menyelesaikan kebutuhan
                    <span class="text-brand-600 font-extrabold border-b-2 border-brand-200" id="typed-text"></span>
                    Anda langsung di lingkungan tempat tinggal.
                </p>

                {{-- Search Box --}}
                <div class="bg-white p-2 rounded-3xl border border-gray-200 shadow-[0_8px_32px_rgba(0,0,0,0.06)] focus-within:ring-4 focus-within:ring-brand-500/10 focus-within:border-brand-300 transition-all duration-300 mb-7">
                    <form action="{{ route('pelanggan.cari') }}" method="GET" class="flex flex-col sm:flex-row items-stretch gap-2" role="search" aria-label="Cari layanan jasa">
                        <div class="relative flex-grow flex items-center pl-4 w-full sm:border-r border-gray-100">
                            <svg class="w-[18px] h-[18px] text-gray-400 flex-shrink-0 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="keyword"
                                placeholder="Cari jasa: servis AC, bersih rumah, les privat..."
                                aria-label="Cari jasa"
                                class="w-full py-3 border-0 focus:ring-0 text-gray-900 placeholder-gray-400 font-medium text-sm outline-none bg-transparent">
                        </div>
                        <button type="submit"
                            class="px-7 py-3 bg-brand-600 text-white rounded-2xl font-bold text-sm hover:bg-brand-700 active:scale-[.97] transition-all shadow-[0_4px_16px_rgba(37,99,235,0.25)] whitespace-nowrap">
                            Cari Sekarang
                        </button>
                    </form>
                </div>

                {{-- Popular tags (SVG only, no emoji) --}}
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-gray-600 mb-10">
                    <span class="font-black text-gray-500 uppercase tracking-wide mr-1" aria-hidden="true">Populer:</span>
                    @foreach([
                        ['Servis AC',    'pelanggan.cari', ['kategori' => 'Reparasi Elektronik']],
                        ['Cuci Kendaraan', 'pelanggan.cari', ['kategori' => 'Perawatan Kendaraan']],
                        ['Les Privat',   'pelanggan.cari', ['kategori' => 'Pendidikan & Les']],
                    ] as [$lbl, $rt, $params])
                    <a href="{{ route($rt, $params) }}"
                       class="px-3.5 py-1.5 bg-gray-50 hover:bg-brand-50 hover:text-brand-600 hover:border-brand-200 rounded-full border border-gray-200 transition-all duration-200">
                        {{ $lbl }}
                    </a>
                    @endforeach
                </div>

                {{-- Mini stats --}}
                <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-8"
                     style="animation: fadeInUp .9s .25s ease both; opacity:0;">
                    <div>
                        <div class="text-2xl font-black text-brand-600">40+</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-wider mt-0.5">Penyedia Aktif</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-indigo-600">250+</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-wider mt-0.5">Pelanggan Puas</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-emerald-600">4.9/5</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-wider mt-0.5">Rating Komunitas</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Interactive radar widget --}}
            <div class="lg:col-span-5 relative mt-10 lg:mt-0 float-slow"
                 style="animation: fadeInUp .8s .15s ease both;">

                <div class="relative z-10 bg-white/80 backdrop-blur-sm p-6 rounded-[2.5rem] border border-gray-100 shadow-[0_20px_60px_rgba(0,0,0,0.06)] max-w-md mx-auto">

                    <p class="text-center text-[10px] font-black text-brand-600 uppercase tracking-widest mb-4 flex items-center justify-center gap-2">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        Radar Jasa Aktif — Klik Icon
                    </p>

                    {{-- Radar map --}}
                    <div class="relative flex items-center justify-center h-64 bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-inner bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px] mb-5">

                        {{-- Radar rings --}}
                        <div class="absolute w-48 h-48 border border-brand-100 rounded-full animate-ping opacity-20"></div>
                        <div class="absolute w-32 h-32 border border-brand-100/50 rounded-full animate-pulse opacity-30"></div>

                        {{-- Central brand logo --}}
                        <div class="relative z-20 w-14 h-14 bg-brand-600 rounded-2xl flex items-center justify-center text-white shadow-[0_8px_24px_rgba(37,99,235,0.35)] border-4 border-white hover:rotate-12 transition-transform duration-300 cursor-default">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/></svg>
                        </div>

                        {{-- Node macro --}}
                        @php
                            $nodes = [
                                ['id'=>1,'pos'=>'top-5 left-8',    'icon'=>'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                                ['id'=>2,'pos'=>'top-8 right-8',   'icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                                ['id'=>3,'pos'=>'bottom-5 left-12','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                ['id'=>4,'pos'=>'bottom-8 right-10','icon'=>'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                            ];
                        @endphp

                        @foreach($nodes as $node)
                        <div @click="activeWorker = {{ $node['id'] }}"
                             :class="activeWorker === {{ $node['id'] }} ? 'border-brand-500 bg-brand-50 scale-110 shadow-md' : 'border-gray-200 bg-white hover:border-brand-300'"
                             class="absolute {{ $node['pos'] }} w-12 h-12 rounded-xl border-2 flex items-center justify-center cursor-pointer transition-all duration-300 z-30">
                            <svg class="w-5 h-5" :class="activeWorker === {{ $node['id'] }} ? 'text-brand-600' : 'text-gray-400'" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $node['icon'] }}"/>
                            </svg>
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border border-white"></span>
                            </span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Worker detail cards --}}
                    @php
                        $workers = [
                            ['id'=>1,'init'=>'S','bg'=>'bg-emerald-500','name'=>'Siti Rahayu',  'jasa'=>'Jasa Bersih Rumah',  'status'=>'Tersedia — RT 02','statusColor'=>'text-emerald-600','tarif'=>'Rp50rb/jam','rating'=>'4.9'],
                            ['id'=>2,'init'=>'B','bg'=>'bg-blue-500',   'name'=>'Budi Santoso', 'jasa'=>'Teknisi AC & Listrik','status'=>'Aktif — RT 03',  'statusColor'=>'text-blue-600',  'tarif'=>'Rp75rb/jam','rating'=>'4.8'],
                            ['id'=>3,'init'=>'D','bg'=>'bg-purple-500', 'name'=>'Dewi Lestari', 'jasa'=>'Les Privat SD/SMP',   'status'=>'Tersedia sore ini','statusColor'=>'text-purple-600','tarif'=>'Rp60rb/jam','rating'=>'5.0'],
                            ['id'=>4,'init'=>'A','bg'=>'bg-orange-500', 'name'=>'Ahmad Dani',   'jasa'=>'Logistik & Angkut',  'status'=>'Siap berangkat', 'statusColor'=>'text-orange-600','tarif'=>'Rp120rb/trip','rating'=>'4.8'],
                        ];
                    @endphp
                    @foreach($workers as $w)
                    <div x-show="activeWorker === {{ $w['id'] }}"
                         x-transition:enter="transition ease-out duration-250"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         {{ $w['id'] > 1 ? 'x-cloak' : '' }}
                         class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl {{ $w['bg'] }} text-white font-black flex items-center justify-center text-sm flex-shrink-0">{{ $w['init'] }}</div>
                        <div class="flex-grow min-w-0">
                            <p class="font-black text-gray-900 text-sm truncate">{{ $w['name'] }}</p>
                            <p class="text-[10px] font-black text-brand-600 uppercase tracking-widest mt-0.5">{{ $w['jasa'] }}</p>
                            <p class="text-[10px] font-semibold {{ $w['statusColor'] }} mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>{{ $w['status'] }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="flex items-center gap-0.5 justify-end mb-1">
                                <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-black text-amber-600">{{ $w['rating'] }}</span>
                            </div>
                            <p class="text-[10px] font-black text-gray-500">{{ $w['tarif'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 2. MARQUEE — Trusted Partners                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="py-10 bg-gray-50 border-y border-gray-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Dipercaya berbagai mitra komunitas lokal</p>
    </div>

    <div class="relative">
        <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-50 to-transparent z-10 pointer-events-none"></div>
        <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-50 to-transparent z-10 pointer-events-none"></div>

        <div class="marquee-wrap">
            @php
                $partners = [
                    ['name' => 'FixIt Pro',     'icon' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z'],
                    ['name' => 'EduHub',        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['name' => 'KomunitasKu',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['name' => 'SwiftServ',    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['name' => 'RumahKu',      'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['name' => 'CleanLife',    'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                    ['name' => 'TechCare',     'icon' => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18'],
                    ['name' => 'GardenPro',    'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                ];
            @endphp

            <div class="animate-marquee items-center gap-16 md:gap-20 px-8">
                @foreach(array_merge($partners, $partners) as $p)
                <div class="flex items-center gap-2.5 text-gray-400 font-extrabold text-base select-none hover:text-gray-700 transition-colors duration-200 whitespace-nowrap flex-shrink-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $p['icon'] }}"/>
                    </svg>
                    {{ $p['name'] }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 3. KATEGORI                                                --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="py-28 bg-gradient-to-b from-white to-gray-50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16" data-aos="fade-up">
            <span class="px-4 py-1.5 bg-brand-50 border border-brand-100 text-brand-700 rounded-full font-black text-xs tracking-widest uppercase">Layanan Terbaik Kami</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mt-4">Kategori Jasa Populer</h2>
            <p class="text-gray-600 font-medium text-sm sm:text-base mt-4 max-w-xl mx-auto leading-relaxed">Temukan mitra penyedia terdekat yang siap membantu segala kebutuhan harian Anda.</p>
        </div>

        @php
            $kategoris = [
                [
                    'nama'   => 'Reparasi Elektronik',
                    'desc'   => 'Servis AC, kelistrikan, pipa ledeng, hingga perbaikan interior rumah harian.',
                    'count'  => '40+',
                    'delay'  => 0,
                    'accent' => 'blue',
                    'icon'   => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                ],
                [
                    'nama'   => 'Kebersihan Rumah',
                    'desc'   => 'Cuci mobil/motor, sedot debu kasur, hingga pembersihan pasca renovasi menyeluruh.',
                    'count'  => '25+',
                    'delay'  => 100,
                    'accent' => 'emerald',
                    'icon'   => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                ],
                [
                    'nama'   => 'Pendidikan & Les',
                    'desc'   => 'Les privat SD/SMP, bimbingan ujian, bahasa asing, kursus seni dan musik pilihan.',
                    'count'  => '18+',
                    'delay'  => 200,
                    'accent' => 'purple',
                    'icon'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                ],
                [
                    'nama'   => 'Perawatan Kendaraan',
                    'desc'   => 'Cuci motor/mobil, ganti oli, tambal ban, servis rutin di lokasi Anda langsung.',
                    'count'  => '12+',
                    'delay'  => 300,
                    'accent' => 'orange',
                    'icon'   => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                ],
            ];
            $accentMap = [
                'blue'   => ['bg'=>'bg-blue-50',   'text'=>'text-blue-600',   'hover'=>'hover:border-blue-400/40   hover:shadow-[0_20px_50px_rgba(59,130,246,0.10)]'],
                'emerald'=> ['bg'=>'bg-emerald-50', 'text'=>'text-emerald-600','hover'=>'hover:border-emerald-400/40 hover:shadow-[0_20px_50px_rgba(16,185,129,0.10)]'],
                'purple' => ['bg'=>'bg-purple-50',  'text'=>'text-purple-600', 'hover'=>'hover:border-purple-400/40  hover:shadow-[0_20px_50px_rgba(139,92,246,0.10)]'],
                'orange' => ['bg'=>'bg-orange-50',  'text'=>'text-orange-600', 'hover'=>'hover:border-orange-400/40  hover:shadow-[0_20px_50px_rgba(249,115,22,0.10)]'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($kategoris as $k)
            @php $ac = $accentMap[$k['accent']]; @endphp
            <a href="{{ route('pelanggan.cari', ['kategori' => $k['nama']]) }}"
               class="tilt-card bg-white rounded-[2rem] p-7 border border-gray-100 flex flex-col items-center text-center group {{ $ac['hover'] }} shadow-sm"
               data-aos="fade-up" data-aos-delay="{{ $k['delay'] }}">

                <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-brand-700 bg-brand-50 px-3 py-1 rounded-full border border-brand-100 mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $k['count'] }} Penyedia Aktif
                </span>

                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-5 {{ $ac['bg'] }} {{ $ac['text'] }} group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $k['icon'] }}"/>
                    </svg>
                </div>

                <h3 class="text-base font-black text-gray-900 mb-2 group-hover:text-brand-600 transition-colors">{{ $k['nama'] }}</h3>
                <p class="text-gray-600 text-xs font-medium leading-relaxed mb-5 flex-grow">{{ $k['desc'] }}</p>

                <span class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2 rounded-full border border-gray-200 text-gray-600 bg-gray-50 group-hover:bg-brand-600 group-hover:text-white group-hover:border-transparent transition-all duration-300">
                    Jelajahi <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 4. KEUNGGULAN                                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="py-28 bg-white relative overflow-hidden">
    <div class="absolute top-1/3 -left-20 w-80 h-80 bg-brand-50 rounded-full filter blur-3xl opacity-60 pointer-events-none"></div>
    <div class="absolute bottom-0 -right-20 w-96 h-96 bg-indigo-50 rounded-full filter blur-3xl opacity-40 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="text-center mb-16" data-aos="fade-up">
            <span class="px-4 py-1.5 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-full font-black text-xs tracking-widest uppercase">Keandalan Terjamin</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mt-4">Mengapa Memilih Neighbourhood?</h2>
            <p class="text-gray-600 font-medium text-sm sm:text-base mt-4 max-w-xl mx-auto leading-relaxed">Kami mengedepankan keamanan, transparansi, dan kecepatan untuk kenyamanan seluruh warga.</p>
        </div>

        @php
            $benefits = [
                [
                    'title' => 'Penyedia Terverifikasi',
                    'desc'  => 'Seluruh berkas identitas (KTP) dan sertifikat keahlian divalidasi langsung secara manual oleh Tim Admin kami.',
                    'bg'    => 'bg-indigo-50',
                    'color' => 'text-indigo-600',
                    'delay' => 0,
                    'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                ],
                [
                    'title' => 'Tarif Transparan',
                    'desc'  => 'Harga disepakati dari awal per jam atau per pengerjaan. Tanpa biaya siluman atau komisi tersembunyi.',
                    'bg'    => 'bg-emerald-50',
                    'color' => 'text-emerald-600',
                    'delay' => 100,
                    'icon'  => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'title' => 'Ulasan Jujur Tetangga',
                    'desc'  => 'Semua review dan rating bintang diberikan langsung oleh warga sekitar yang sudah memakai jasa tersebut.',
                    'bg'    => 'bg-amber-50',
                    'color' => 'text-amber-600',
                    'delay' => 200,
                    'icon'  => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.237.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.572-.38-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z',
                ],
                [
                    'title' => 'Jangkauan Hiper-Lokal',
                    'desc'  => 'Fokus memprioritaskan penyedia terdekat di sekitar RT/RW Anda untuk respons penanganan super cepat.',
                    'bg'    => 'bg-brand-50',
                    'color' => 'text-brand-600',
                    'delay' => 300,
                    'icon'  => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($benefits as $b)
            <div class="p-7 bg-white rounded-[2rem] border border-gray-100 hover:border-gray-200 hover:shadow-xl transition-all duration-400 flex flex-col items-center text-center group tilt-card"
                 data-aos="fade-up" data-aos-delay="{{ $b['delay'] }}">
                <div class="w-14 h-14 {{ $b['bg'] }} {{ $b['color'] }} rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $b['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-gray-900 mb-2">{{ $b['title'] }}</h3>
                <p class="text-gray-600 text-sm font-medium leading-relaxed">{{ $b['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 5. CARA KERJA                                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="py-28 bg-gray-50 border-y border-gray-100 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="text-center mb-16" data-aos="fade-up">
            <span class="px-4 py-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-full font-black text-xs tracking-widest uppercase">Proses Alur Cepat</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mt-4">Cara Kerja Platform</h2>
            <p class="text-gray-600 font-medium text-sm sm:text-base mt-4 max-w-xl mx-auto">Hanya butuh 3 langkah instan untuk mulai memesan layanan jasa tetangga terdekat Anda.</p>
        </div>

        <div class="relative">
            {{-- Connector line --}}
            <div class="hidden lg:block absolute top-[4.5rem] left-[calc(16.67%+2rem)] right-[calc(16.67%+2rem)] h-px bg-gradient-to-r from-brand-300 via-indigo-300 to-purple-300 z-0 opacity-50"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 relative z-10">
                @php
                    $steps = [
                        ['num'=>'1','color'=>'bg-brand-600','shadow'=>'shadow-[0_8px_20px_rgba(37,99,235,0.3)]','title'=>'Cari Layanan','desc'=>'Temukan jasa yang Anda butuhkan melalui pencarian pintar atau telusuri kategori terdekat di beranda utama.','hover'=>'group-hover:text-brand-600','delay'=>0],
                        ['num'=>'2','color'=>'bg-indigo-600','shadow'=>'shadow-[0_8px_20px_rgba(79,70,229,0.3)]','title'=>'Jadwalkan Booking','desc'=>'Pilih waktu kunjungan, komunikasikan kebutuhan khusus, lalu kirimkan pesanan langsung ke penyedia.','hover'=>'group-hover:text-indigo-600','delay'=>100],
                        ['num'=>'3','color'=>'bg-purple-600','shadow'=>'shadow-[0_8px_20px_rgba(139,92,246,0.3)]','title'=>'Bayar & Beri Ulasan','desc'=>'Konfirmasi pembayaran setelah pekerjaan selesai, lalu bantu tetangga lain dengan ulasan bintang jujur Anda.','hover'=>'group-hover:text-purple-600','delay'=>200],
                    ];
                @endphp
                @foreach($steps as $s)
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-400 text-center flex flex-col items-center group tilt-card"
                     data-aos="fade-up" data-aos-delay="{{ $s['delay'] }}">
                    <div class="w-16 h-16 {{ $s['color'] }} text-white rounded-full flex items-center justify-center text-2xl font-black {{ $s['shadow'] }} mb-7 border-4 border-white group-hover:scale-110 transition-transform duration-300">
                        {{ $s['num'] }}
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3 {{ $s['hover'] }} transition-colors">{{ $s['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed font-medium">{{ $s['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 6. TESTIMONIALS — Fixed filter + auto-slide carousel       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@php
    $testimonials = [
        [
            'initial' => 'RM', 'color' => 'from-brand-500 to-indigo-600',
            'name' => 'Rina Marlina', 'role' => 'Pelanggan Jasa', 'type' => 'pelanggan',
            'roleColor' => 'bg-brand-50 text-brand-700',
            'label' => 'Pelanggan Aktif',
            'text' => 'Sangat terbantu mencari guru privat matematika terdekat untuk anak saya yang masih SD. Layanannya transparan, pembayarannya aman, dan tutornya sangat bersahabat serta profesional.',
            'tag' => 'Les Privat · RT 05',
        ],
        [
            'initial' => 'BS', 'color' => 'from-emerald-500 to-teal-600',
            'name' => 'Budi Santoso', 'role' => 'Penyedia Jasa', 'type' => 'penyedia',
            'roleColor' => 'bg-emerald-50 text-emerald-700',
            'label' => 'Mitra Terverifikasi',
            'text' => 'Bergabung di Neighbourhood Services melipatgandakan penghasilan saya dari pesanan warga perumahan sekitar. Praktis, efisien, dan platform-nya mudah digunakan setiap hari.',
            'tag' => 'Teknisi Listrik · RT 03',
        ],
        [
            'initial' => 'AW', 'color' => 'from-purple-500 to-fuchsia-600',
            'name' => 'Andi Wijaya', 'role' => 'Pelanggan Jasa', 'type' => 'pelanggan',
            'roleColor' => 'bg-purple-50 text-purple-700',
            'label' => 'Pelanggan Aktif',
            'text' => 'Pindahan kos jadi super lancar dan terjangkau berkat jasa pickup terdekat yang saya temukan di sini. Supirnya sigap membantu angkat barang berat. Sangat direkomendasikan!',
            'tag' => 'Angkut Barang · RT 07',
        ],
        [
            'initial' => 'DL', 'color' => 'from-amber-500 to-orange-600',
            'name' => 'Dewi Lestari', 'role' => 'Penyedia Jasa', 'type' => 'penyedia',
            'roleColor' => 'bg-amber-50 text-amber-700',
            'label' => 'Mitra Terverifikasi',
            'text' => 'Sebagai guru les privat, platform ini membantu saya mendapatkan murid baru tanpa repot promosi. Sistem booking dan notifikasi-nya sangat membantu pengaturan jadwal harian.',
            'tag' => 'Les Privat · RT 01',
        ],
        [
            'initial' => 'SR', 'color' => 'from-rose-500 to-pink-600',
            'name' => 'Sari Rahayu', 'role' => 'Pelanggan Jasa', 'type' => 'pelanggan',
            'roleColor' => 'bg-rose-50 text-rose-700',
            'label' => 'Pelanggan Aktif',
            'text' => 'AC rumah saya rusak mendadak di tengah terik. Teknisi datang dalam 2 jam dan langsung beres. Harga di muka, tidak ada biaya kejutan sama sekali. Luar biasa responsif!',
            'tag' => 'Servis AC · RT 02',
        ],
    ];
    $testimonialTypes = array_column($testimonials, 'type');
@endphp

<section class="py-32 bg-white relative overflow-hidden"
    x-data="{
        current: 0,
        filter: 'semua',
        progTimer: null,
        progress: 0,
        INTERVAL: 5500,
        types: {{ json_encode($testimonialTypes) }},
        get total() { return this.types.length; },
        matchesFilter(i) { return this.filter === 'semua' || this.types[i] === this.filter; },
        init() { this.startAuto(); },
        startAuto() {
            clearInterval(this.progTimer);
            this.progress = 0;
            const step = 50, inc = (step / this.INTERVAL) * 100;
            this.progTimer = setInterval(() => {
                this.progress = Math.min(this.progress + inc, 100);
                if (this.progress >= 100) { this.progress = 0; this.advance(1); }
            }, step);
        },
        advance(dir) {
            let tries = this.total, next = this.current;
            do { next = (next + dir + this.total) % this.total; tries--; }
            while (!this.matchesFilter(next) && tries > 0);
            if (this.matchesFilter(next)) this.current = next;
            this.startAuto();
        },
        setFilter(val) {
            this.filter = val;
            for (let i = 0; i < this.total; i++) {
                if (this.matchesFilter(i)) { this.current = i; break; }
            }
            this.startAuto();
        }
    }"
    @keydown.window.arrow-right="advance(1)"
    @keydown.window.arrow-left="advance(-1)">

    {{-- Decorative blobs --}}
    <div class="absolute -top-40 -left-40 w-[32rem] h-[32rem] rounded-full bg-indigo-50/70 filter blur-[80px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-[32rem] h-[32rem] rounded-full bg-brand-50/70 filter blur-[80px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header --}}
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-brand-50 border border-brand-100 text-brand-700 rounded-full font-black text-xs tracking-widest uppercase mb-5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
                </span>
                Ulasan Warga
            </span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight leading-[1.1] mt-2">
                Kisah Sukses<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-600">Tetangga Anda</span>
            </h2>
            <p class="text-gray-600 font-medium text-base mt-4 max-w-lg mx-auto leading-relaxed">Pengalaman nyata dari warga yang hidupnya lebih mudah dan penyedia jasa yang bisnisnya berkembang.</p>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex justify-center gap-2 mb-10" data-aos="fade-up" data-aos-delay="100" role="group" aria-label="Filter testimoni">
            @foreach(['semua' => 'Semua', 'pelanggan' => 'Pelanggan', 'penyedia' => 'Penyedia Jasa'] as $val => $label)
            <button type="button" @click="setFilter('{{ $val }}')"
                :aria-pressed="filter === '{{ $val }}'"
                :class="filter === '{{ $val }}'
                    ? 'bg-brand-600 text-white border-brand-600 shadow-md shadow-brand-200/50'
                    : 'bg-white text-gray-500 border-gray-200 hover:border-brand-300 hover:text-brand-600'"
                class="px-5 py-2 rounded-full text-xs font-black border transition-all duration-300">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Carousel --}}
        <div class="relative max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">

            {{-- Nav buttons --}}
            <button type="button" @click="advance(-1)" aria-label="Testimoni sebelumnya" aria-controls="testimonial-slides"
                class="absolute -left-5 md:-left-16 top-1/2 -translate-y-1/2 z-20 w-11 h-11 bg-white rounded-full border border-gray-200 shadow-md flex items-center justify-center text-gray-400 hover:text-brand-600 hover:border-brand-300 hover:shadow-lg active:scale-95 transition-all">
                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" @click="advance(1)" aria-label="Testimoni berikutnya" aria-controls="testimonial-slides"
                class="absolute -right-5 md:-right-16 top-1/2 -translate-y-1/2 z-20 w-11 h-11 bg-white rounded-full border border-gray-200 shadow-md flex items-center justify-center text-gray-400 hover:text-brand-600 hover:border-brand-300 hover:shadow-lg active:scale-95 transition-all">
                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Slides --}}
            <div id="testimonial-slides" class="relative overflow-hidden rounded-3xl min-h-[320px]" aria-live="polite" aria-atomic="true">
                @foreach($testimonials as $idx => $t)
                <div
                    x-show="current === {{ $idx }} && matchesFilter({{ $idx }})"
                    x-transition:enter="transition duration-500 ease-out"
                    x-transition:enter-start="opacity-0 translate-x-8 scale-[.98]"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition duration-300 ease-in"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 -translate-x-8 scale-[.98]"
                    x-cloak
                    class="bg-white border border-gray-100 rounded-3xl p-8 md:p-12 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.07)] relative overflow-hidden">

                    {{-- Large decorative quote mark --}}
                    <div class="absolute -top-3 -right-1 text-[8rem] font-serif text-gray-100 select-none leading-none pointer-events-none">"</div>

                    {{-- Role + location badges --}}
                    <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                        <span class="text-[10px] font-black px-3 py-1.5 rounded-full bg-gray-50 border border-gray-100 text-gray-500 uppercase tracking-wider">{{ $t['label'] }}</span>
                        <span class="text-[10px] font-bold px-3 py-1 rounded-full {{ $t['roleColor'] }}">{{ $t['tag'] }}</span>
                    </div>

                    {{-- Stars --}}
                    <div class="flex gap-0.5 mb-5">
                        @for($s=0;$s<5;$s++)
                        <svg class="w-[18px] h-[18px] text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>

                    {{-- Quote --}}
                    <p class="text-gray-700 text-lg font-medium leading-relaxed italic mb-8 relative z-10">
                        "{{ $t['text'] }}"
                    </p>

                    {{-- Divider --}}
                    <div class="h-px bg-gradient-to-r from-transparent via-gray-100 to-transparent mb-6"></div>

                    {{-- Author --}}
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br {{ $t['color'] }} text-white font-black flex items-center justify-center text-sm shadow-md flex-shrink-0">
                            {{ $t['initial'] }}
                        </div>
                        <div>
                            <div class="font-black text-gray-900 text-sm">{{ $t['name'] }}</div>
                            <div class="text-[10px] font-black uppercase tracking-wider mt-0.5 {{ $t['roleColor'] }} inline-block px-2 py-0.5 rounded-full">{{ $t['role'] }}</div>
                        </div>
                        <div class="ml-auto text-right hidden sm:block">
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ulasan Terverifikasi</div>
                            <div class="flex items-center gap-1 mt-1 justify-end">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-[10px] font-black text-emerald-600">Pengguna Nyata</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Progress bar --}}
            <div class="mt-4 h-0.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-brand-500 to-indigo-500 rounded-full transition-none"
                     :style="`width: ${progress}%`"></div>
            </div>

            {{-- Dot indicators --}}
            <div class="flex items-center justify-center gap-2 mt-5" role="tablist" aria-label="Navigasi testimoni">
                @foreach($testimonials as $idx => $t)
                <button type="button"
                    @click="current={{ $idx }}; startAuto()"
                    x-show="matchesFilter({{ $idx }})"
                    aria-label="Testimoni {{ $idx + 1 }}: {{ $t['name'] }}"
                    :aria-selected="current === {{ $idx }}"
                    role="tab"
                    :class="current === {{ $idx }} ? 'w-6 bg-brand-600' : 'w-2 bg-gray-300 hover:bg-gray-400'"
                    class="h-2 rounded-full transition-all duration-300">
                </button>
                @endforeach
            </div>
        </div>

        {{-- Trust strip --}}
        <div class="mt-16 flex flex-wrap items-center justify-center gap-6 text-xs font-bold text-gray-400" data-aos="fade-up" data-aos-delay="300">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Semua ulasan dari pengguna nyata
            </span>
            <span class="w-1 h-1 rounded-full bg-gray-200"></span>
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Rating rata-rata 4.9 dari 5
            </span>
            <span class="w-1 h-1 rounded-full bg-gray-200"></span>
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                250+ ulasan dikumpulkan
            </span>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 7. STATS — Animated counters + headline                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden"
    x-data="{
        visible: false,
        counters: [
            { label: 'Kota Aktif',       from:0, to:50,  suffix:'+',  decimals:0, display:'0', pct:78 },
            { label: 'Penyedia Jasa',    from:0, to:10,  suffix:'K+', decimals:0, display:'0', pct:88 },
            { label: 'Pelanggan Puas',   from:0, to:50,  suffix:'K+', decimals:0, display:'0', pct:95 },
            { label: 'Rating Rata-rata', from:0, to:4.9, suffix:'/5', decimals:1, display:'0', pct:98 },
        ],
        animate() {
            this.counters.forEach((c,i) => {
                const dur=1800, steps=60, delay=i*150;
                setTimeout(() => {
                    let step=0;
                    const t = setInterval(() => {
                        step++;
                        const p = 1-Math.pow(1-step/steps,3);
                        c.display = c.decimals>0 ? (c.from+(c.to-c.from)*p).toFixed(c.decimals) : Math.round(c.from+(c.to-c.from)*p).toString();
                        if(step>=steps){ c.display=c.decimals>0?c.to.toFixed(c.decimals):c.to.toString(); clearInterval(t); }
                    }, dur/steps);
                }, delay);
            });
        }
    }"
    x-intersect.once="visible=true; animate()">

    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-10%,rgba(99,102,241,0.18),transparent)]"></div>
    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.018)_1px,transparent_1px),linear-gradient(to_right,rgba(255,255,255,0.018)_1px,transparent_1px)] bg-[size:3rem_3rem]"></div>
    {{-- Glowing orbs --}}
    <div class="absolute top-0 left-1/4 w-[600px] h-[300px] bg-brand-600/10 rounded-full filter blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[300px] bg-purple-600/10 rounded-full filter blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Section header --}}
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.06] border border-white/10 text-white/50 font-black text-[10px] tracking-widest uppercase mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
                Platform dalam Angka
            </span>
            <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
                Kepercayaan yang<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-indigo-400">Terus Berkembang</span>
            </h2>
            <p class="text-white/35 font-medium text-sm mt-4 max-w-md mx-auto leading-relaxed">
                Data nyata dari pertumbuhan komunitas hiper-lokal yang kami bangun setiap harinya.
            </p>
        </div>

        @php
            $statIcons = [
                'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.237.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.572-.38-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z',
            ];
            $statGrads  = ['from-brand-400 to-brand-600', 'from-emerald-400 to-teal-600', 'from-indigo-400 to-purple-600', 'from-amber-400 to-orange-500'];
            $statBars   = ['bg-gradient-to-r from-brand-400 to-brand-600', 'bg-gradient-to-r from-emerald-400 to-teal-600', 'bg-gradient-to-r from-indigo-400 to-purple-600', 'bg-gradient-to-r from-amber-400 to-orange-500'];
            $statDescs  = ['Jangkauan wilayah', 'Mitra terverifikasi', 'Warga terbantu', 'Skor kepuasan'];
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach([0,1,2,3] as $i)
            <div data-aos="fade-up" data-aos-delay="{{ $i*100 }}"
                 class="group relative bg-white/[0.04] hover:bg-white/[0.07] border border-white/[0.06] hover:border-white/[0.15] p-7 rounded-[2rem] backdrop-blur-sm flex flex-col items-start text-left transition-all duration-300 hover:-translate-y-2 cursor-default overflow-hidden">

                {{-- Subtle corner gradient --}}
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl {{ $statGrads[$i] }} opacity-[0.06] rounded-bl-[3rem]"></div>

                <div class="w-[50px] h-[50px] bg-gradient-to-br {{ $statGrads[$i] }} text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $statIcons[$i] }}"/>
                    </svg>
                </div>

                <div class="text-4xl md:text-5xl font-black text-white mb-1 tracking-tighter leading-none tabular-nums">
                    <span x-text="counters[{{ $i }}].display">0</span><span class="text-white/30 text-2xl font-black" x-text="counters[{{ $i }}].suffix"></span>
                </div>
                <div class="text-white/60 font-black tracking-wider text-[10px] uppercase mb-1" x-text="counters[{{ $i }}].label"></div>
                <div class="text-white/25 font-semibold text-[10px] mb-4">{{ $statDescs[$i] }}</div>

                <div class="w-full h-0.5 bg-white/[0.06] rounded-full overflow-hidden mt-auto">
                    <div class="{{ $statBars[$i] }} h-full rounded-full transition-all duration-[2s] ease-out"
                         :style="visible ? `width: ${counters[{{ $i }}].pct}%` : 'width: 0%'"></div>
                </div>
                <div class="flex justify-between w-full mt-1.5">
                    <span class="text-white/20 text-[9px] font-black">0%</span>
                    <span class="text-white/30 text-[9px] font-black tabular-nums" x-text="`${counters[{{ $i }}].pct}%`"></span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bottom trust badges row --}}
        <div class="mt-16 pt-10 border-t border-white/[0.06] flex items-center justify-center gap-6 md:gap-12 flex-wrap" data-aos="fade-up" data-aos-delay="300">
            @foreach([
                ['icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                          'label'=>'Pembayaran Aman',   'sub'=>'Terproteksi penuh'],
                ['icon'=>'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',  'label'=>'Data Terenkripsi', 'sub'=>'256-bit SSL'],
                ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z',                                               'label'=>'Respons Cepat',    'sub'=>'Rata-rata < 2 jam'],
                ['icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label'=>'Hiper-Lokal', 'sub'=>'RT/RW terdekat'],
            ] as $badge)
            <div class="flex flex-col items-center gap-1.5 text-center">
                <div class="w-10 h-10 rounded-2xl bg-white/[0.05] border border-white/[0.07] flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-white/40" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $badge['icon'] }}"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-white/40 uppercase tracking-wider">{{ $badge['label'] }}</span>
                <span class="text-[9px] font-semibold text-white/20">{{ $badge['sub'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- 8. CTA — Split layout, rich visual                         --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<section class="py-28 bg-white relative overflow-hidden">
    {{-- Background blobs --}}
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-50 rounded-full filter blur-3xl opacity-60 pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-brand-50 rounded-full filter blur-3xl opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

            {{-- LEFT — Content --}}
            <div data-aos="fade-right">
                <span class="px-4 py-1.5 bg-brand-50 border border-brand-100 text-brand-700 rounded-full font-black text-xs tracking-widest uppercase">
                    Bergabung Sekarang
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight mt-5 mb-5 leading-[1.08]">
                    Wujudkan Komunitas<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-indigo-600">yang Saling Peduli</span>
                </h2>
                <p class="text-gray-600 font-medium text-base leading-relaxed mb-8 max-w-lg">
                    Ribuan warga sudah membuktikan kemudahan menemukan jasa profesional di lingkungan sendiri. Giliran Anda memulai perjalanan bersama platform hiper-lokal terpercaya ini.
                </p>

                {{-- Feature checklist --}}
                <ul class="space-y-3 mb-10">
                    @foreach([
                        ['Daftar gratis, tanpa biaya langganan bulanan', 'brand'],
                        ['Penyedia terverifikasi identitas oleh admin', 'emerald'],
                        ['Booking fleksibel: per jam, per proyek, atau paket', 'indigo'],
                        ['Ulasan jujur dari sesama warga sekitar', 'amber'],
                    ] as [$feat, $c])
                    <li class="flex items-center gap-3 text-sm font-semibold text-gray-700">
                        <span class="w-5 h-5 rounded-full bg-{{ $c }}-50 border border-{{ $c }}-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-{{ $c }}-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl font-black text-sm shadow-lg shadow-brand-500/25 hover:-translate-y-1 hover:shadow-xl active:scale-[.97] transition-all duration-300">
                        Mulai Sebagai Pelanggan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('register', ['peran' => 'penyedia']) }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 border-2 border-gray-200 hover:border-brand-300 text-gray-700 hover:text-brand-600 rounded-2xl font-black text-sm hover:-translate-y-0.5 active:scale-[.97] transition-all duration-300">
                        Daftar Sebagai Penyedia
                    </a>
                </div>
            </div>

            {{-- RIGHT — Visual card stack --}}
            <div class="relative flex items-center justify-center" data-aos="fade-left" data-aos-delay="100">

                {{-- Shadow card behind --}}
                <div class="absolute top-6 -right-3 w-[85%] h-full bg-indigo-100 rounded-[2.5rem] transform rotate-2 opacity-60"></div>
                <div class="absolute top-3 -right-1.5 w-[90%] h-full bg-brand-100 rounded-[2.5rem] transform rotate-1 opacity-40"></div>

                {{-- Main card --}}
                <div class="relative z-10 w-full bg-gradient-to-br from-slate-900 to-indigo-950 rounded-[2.5rem] p-8 shadow-[0_30px_60px_rgba(37,99,235,0.2)] overflow-hidden border border-white/10">
                    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:2.5rem_2.5rem]"></div>
                    <div class="absolute top-0 right-0 w-48 h-48 bg-brand-600/20 rounded-full filter blur-3xl"></div>

                    <div class="relative z-10">
                        {{-- App header mock --}}
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-9 h-9 bg-brand-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/></svg>
                            </div>
                            <span class="text-white font-black text-sm">Neighbourhood</span>
                            <span class="ml-auto w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>

                        {{-- Order notification mock --}}
                        <div class="bg-white/[0.07] border border-white/10 rounded-2xl p-4 mb-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white text-xs font-black">Pesanan Disetujui</p>
                                    <p class="text-white/40 text-[10px]">Teknisi AC · Hari ini, 14:30</p>
                                </div>
                                <span class="ml-auto text-emerald-400 text-[10px] font-black">Baru</span>
                            </div>
                            <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full w-[70%] bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full"></div>
                            </div>
                        </div>

                        {{-- Provider stats mock --}}
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            @foreach([['Pesanan','24','text-brand-400'],['Rating','4.9','text-amber-400'],['Pendapatan','Rp2.4Jt','text-emerald-400']] as [$lbl,$val,$col])
                            <div class="bg-white/[0.05] border border-white/[0.07] rounded-2xl p-3 text-center">
                                <p class="text-[11px] font-black {{ $col }} mb-0.5">{{ $val }}</p>
                                <p class="text-white/25 text-[9px] font-semibold uppercase tracking-wide">{{ $lbl }}</p>
                            </div>
                            @endforeach
                        </div>

                        {{-- CTA button inside mock --}}
                        <div class="bg-brand-600 rounded-2xl py-3 text-center">
                            <p class="text-white font-black text-xs">Mulai Terima Pesanan Sekarang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* Typed.js */
    if (document.getElementById('typed-text')) {
        new Typed('#typed-text', {
            strings: ['perbaikan AC', 'kebersihan rumah', 'bimbingan belajar', 'angkut barang', 'servis kendaraan'],
            typeSpeed: 60,
            backSpeed: 35,
            backDelay: 2200,
            loop: true,
            showCursor: true,
            cursorChar: '|',
        });
    }

    /* Scroll-reveal for mini stats row (no AOS on that element) */
    const statsRow = document.querySelector('[style*="animation: fadeInUp .9s .25s"]');
    if (statsRow) {
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.style.opacity = '1'; obs.unobserve(e.target); } });
        }, { threshold: 0.2 });
        obs.observe(statsRow);
    }
});
</script>
@endpush
