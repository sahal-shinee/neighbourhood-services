@extends('layouts.app')
@section('title', 'Cari Penyedia Jasa')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnLokasi  = document.getElementById('btn-get-location');
    const inputLat   = document.getElementById('lat');
    const inputLng   = document.getElementById('lng');
    const txtStatus  = document.getElementById('lokasi-status');
    const sortSelect = document.getElementById('sort_by');

    if (btnLokasi) {
        btnLokasi.addEventListener('click', function () {
            if (!('geolocation' in navigator)) {
                alert('Browser Anda tidak mendukung fitur GPS.');
                return;
            }
            txtStatus.textContent = 'Mendeteksi lokasi...';
            txtStatus.className   = 'text-xs font-bold text-brand-600';

            navigator.geolocation.getCurrentPosition(function (pos) {
                inputLat.value = pos.coords.latitude;
                inputLng.value = pos.coords.longitude;
                txtStatus.textContent = 'Lokasi aktif. Klik Cari untuk memperbarui hasil.';
                txtStatus.className   = 'text-xs font-bold text-emerald-600';
                // Auto-switch to nearest if currently on default
                if (sortSelect && sortSelect.value === 'default') {
                    sortSelect.value = 'terdekat';
                }
            }, function () {
                txtStatus.textContent = 'Gagal mendapatkan lokasi. Pastikan izin GPS aktif.';
                txtStatus.className   = 'text-xs font-bold text-red-500';
            }, { enableHighAccuracy: true, timeout: 10000 });
        });
    }
});
</script>
@endpush

@section('content')

{{-- ── Search Panel ──────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-7 sm:p-9 mb-8">
    <form id="search-form" action="{{ route('pelanggan.cari') }}" method="GET" class="space-y-6">

        <input type="hidden" name="lat" id="lat" value="{{ $lat }}">
        <input type="hidden" name="lng" id="lng" value="{{ $lng }}">

        {{-- Header row --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-5 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-black text-gray-900 tracking-tight">Temukan Jasa Terbaik</h2>
                <p class="text-xs font-semibold text-gray-400 mt-1">Cari tanpa batas — aktifkan GPS untuk menyortir berdasarkan jarak.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="btn-get-location"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-xl font-bold text-xs border border-brand-100 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Aktifkan GPS
                </button>
                <span id="lokasi-status"
                      class="text-xs font-bold {{ ($lat && $lng) ? 'text-emerald-600' : 'text-gray-400' }}">
                    {{ ($lat && $lng) ? 'Lokasi aktif' : 'GPS belum aktif — tampilkan semua jasa' }}
                </span>
            </div>
        </div>

        {{-- Filter row --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

            {{-- Keyword --}}
            <div class="md:col-span-5">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama / Kata Kunci</label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="keyword" value="{{ $keyword }}"
                        placeholder="Tukang AC, Cuci Mobil, Les Privat..."
                        class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl pl-11 pr-4 py-3.5 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all text-sm font-medium outline-none">
                </div>
            </div>

            {{-- Kategori --}}
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kategori</label>
                <select name="kategori"
                    class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3.5 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all text-sm font-semibold outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $k)
                    <option value="{{ $k->nama_kategori }}" {{ $kategori == $k->nama_kategori ? 'selected' : '' }}>
                        {{ $k->ikon_kategori }} {{ $k->nama_kategori }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Urutkan --}}
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Urutkan</label>
                <select name="sort_by" id="sort_by"
                    class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3.5 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all text-sm font-semibold outline-none cursor-pointer">
                    <option value="default"  {{ $sortBy === 'default'  ? 'selected' : '' }}>Terbaru</option>
                    <option value="terdekat" {{ $sortBy === 'terdekat' ? 'selected' : '' }}>Terdekat{{ !$hasLocation ? ' (butuh GPS)' : '' }}</option>
                    <option value="terjauh"  {{ $sortBy === 'terjauh'  ? 'selected' : '' }}>Terjauh{{ !$hasLocation ? ' (butuh GPS)' : '' }}</option>
                    <option value="termurah" {{ $sortBy === 'termurah' ? 'selected' : '' }}>Termurah</option>
                    <option value="termahal" {{ $sortBy === 'termahal' ? 'selected' : '' }}>Termahal</option>
                </select>
            </div>

            {{-- Submit --}}
            <div class="md:col-span-2 flex items-end">
                <button type="submit"
                    class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold px-5 py-4 rounded-2xl transition-all shadow-[0_6px_18px_rgba(37,99,235,0.22)] hover:-translate-y-0.5 active:translate-y-0 text-sm">
                    Cari
                </button>
            </div>
        </div>

        {{-- GPS info note when distance sort is selected without location --}}
        @if(in_array($sortBy, ['terdekat','terjauh']) && !$hasLocation)
        <div class="flex items-center gap-2.5 px-4 py-3 bg-amber-50 border border-amber-100 rounded-2xl text-xs font-semibold text-amber-700">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Urutan berdasarkan jarak memerlukan GPS aktif. Aktifkan GPS di atas lalu klik Cari, atau pilih urutan lainnya.
        </div>
        @endif
    </form>
</div>

{{-- ── Results Header ─────────────────────────────────────────────────────── --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-xl font-black text-gray-900 tracking-tight">
            @if($keyword || $kategori)
                Hasil untuk
                @if($keyword) "<span class="text-brand-600">{{ $keyword }}</span>" @endif
                @if($kategori) <span class="text-indigo-600">{{ $kategori }}</span> @endif
            @else
                Semua Layanan Tersedia
            @endif
        </h2>
        <p class="text-xs font-semibold text-gray-400 mt-0.5">
            Ditemukan <span class="font-black text-gray-700">{{ $jasa->total() }}</span> layanan aktif
            @if($hasLocation && in_array($sortBy, ['terdekat','terjauh']))
                — diurutkan berdasarkan {{ $sortBy === 'terdekat' ? 'yang paling dekat' : 'yang paling jauh' }}
            @elseif($sortBy === 'termurah')
                — diurutkan dari harga termurah
            @elseif($sortBy === 'termahal')
                — diurutkan dari harga termahal
            @endif
        </p>
    </div>

    @if($hasLocation)
    <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-2 rounded-xl self-start sm:self-center">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
        GPS Aktif
    </span>
    @endif
</div>

{{-- ── Results Grid ────────────────────────────────────────────────────────── --}}
@if($jasa->total() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($jasa as $j)
            <x-service-card :jasa="$j" :isFavorit="in_array($j->id_jasa, $favoritIds)" />
        @endforeach
    </div>
    <div class="mt-10">
        {{ $jasa->links('components.pagination') }}
    </div>
@else
    <div class="text-center py-20 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm max-w-2xl mx-auto p-10">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-200">
            <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-black text-gray-900">Tidak Ada Jasa Ditemukan</h3>
        <p class="text-sm font-semibold text-gray-400 mt-2 leading-relaxed max-w-sm mx-auto">
            Coba hapus filter kategori, ubah kata kunci pencarian, atau pilih urutan yang berbeda.
        </p>
        <a href="{{ route('pelanggan.cari') }}"
           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-brand-50 text-brand-600 border border-brand-100 rounded-xl font-bold text-sm hover:bg-brand-600 hover:text-white transition-all">
            Reset Pencarian
        </a>
    </div>
@endif

@endsection
