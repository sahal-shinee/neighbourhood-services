@extends('layouts.app')
@section('title', 'Tambah Layanan Baru')
@section('header', 'Tambah Layanan Baru')

@section('content')

<div class="max-w-3xl mx-auto" x-data="jasaForm('{{ old('tipe_tarif', 'per_jam') }}')">
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.04)] overflow-hidden">

        {{-- Header --}}
        <div class="h-28 bg-gradient-to-r from-indigo-600 to-indigo-700 relative flex items-end px-8 pb-6">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12)_0%,transparent_60%)]"></div>
            <div class="relative text-white">
                <h3 class="text-lg font-black tracking-tight">Buat Layanan Baru</h3>
                <p class="text-xs text-indigo-200 font-medium mt-0.5">Isi detail layanan yang akan ditawarkan kepada pelanggan.</p>
            </div>
        </div>

        <form action="{{ route('penyedia.jasa.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-7">
            @csrf

            {{-- Kategori --}}
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                    Kategori Layanan <span class="text-rose-500">*</span>
                </label>
                <select name="kategori_jasa" required
                    class="block w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 focus:bg-white outline-none transition-all">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->nama_kategori }}" {{ old('kategori_jasa') == $k->nama_kategori ? 'selected' : '' }}>
                            {{ $k->ikon_kategori }} {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_jasa') <p class="mt-1.5 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Nama Jasa --}}
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                    Nama Layanan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama_jasa" value="{{ old('nama_jasa') }}" required
                    placeholder="Contoh: Jasa Cuci AC Split Panggilan, Pembuatan Website Toko Online..."
                    class="block w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 focus:bg-white outline-none transition-all">
                @error('nama_jasa') <p class="mt-1.5 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                    Deskripsi Layanan <span class="text-rose-500">*</span>
                </label>
                <textarea name="deskripsi_jasa" rows="4" required
                    placeholder="Jelaskan apa yang Anda kerjakan, pengalaman Anda, alat yang digunakan, dan hal-hal yang perlu diketahui pelanggan..."
                    class="block w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 focus:bg-white outline-none transition-all resize-none">{{ old('deskripsi_jasa') }}</textarea>
                @error('deskripsi_jasa') <p class="mt-1.5 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- ===================== TIPE TARIF SELECTOR ===================== --}}
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-3">
                    Model Tarif <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([
                        'per_jam'        => ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Per Jam', 'desc' => 'Dihitung per jam pengerjaan'],
                        'per_pengerjaan' => ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Per Pengerjaan', 'desc' => 'Harga flat satu kali kerja'],
                        'paket'          => ['icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'label' => 'Paket / Tier', 'desc' => 'Beberapa pilihan harga'],
                    ] as $val => $opt)
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_tarif" value="{{ $val }}" x-model="tipeTarif" class="sr-only">
                            <div :class="tipeTarif === '{{ $val }}'
                                    ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-400/20'
                                    : 'border-gray-200 bg-gray-50 hover:border-gray-300'"
                                 class="rounded-2xl border-2 p-4 text-center transition-all select-none">
                                <div class="w-9 h-9 mx-auto mb-2 rounded-xl flex items-center justify-center"
                                     :class="tipeTarif === '{{ $val }}' ? 'bg-indigo-100' : 'bg-gray-100'">
                                    <svg class="w-5 h-5" :class="tipeTarif === '{{ $val }}' ? 'text-indigo-600' : 'text-gray-400'"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $opt['icon'] }}"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-black"
                                   :class="tipeTarif === '{{ $val }}' ? 'text-indigo-700' : 'text-gray-700'">
                                    {{ $opt['label'] }}
                                </p>
                                <p class="text-[10px] font-medium mt-0.5"
                                   :class="tipeTarif === '{{ $val }}' ? 'text-indigo-400' : 'text-gray-400'">
                                    {{ $opt['desc'] }}
                                </p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('tipe_tarif') <p class="mt-1.5 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Tarif (per_jam / per_pengerjaan) --}}
            <div x-show="tipeTarif !== 'paket'" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                    <span x-text="tipeTarif === 'per_jam' ? 'Tarif per Jam (Rp)' : 'Tarif per Pengerjaan (Rp)'"></span>
                    <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                    <input type="number" name="tarif_per_jam" value="{{ old('tarif_per_jam') }}"
                        :required="tipeTarif !== 'paket'"
                        min="1000" step="500" placeholder="50000"
                        class="block w-full rounded-2xl border border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium text-gray-900 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 focus:bg-white outline-none transition-all">
                </div>
                <p class="text-[11px] text-gray-400 font-medium mt-1.5"
                   x-text="tipeTarif === 'per_jam' ? 'Harga akan dikalikan dengan durasi jam yang dipilih pelanggan.' : 'Harga tetap, tidak tergantung durasi pengerjaan.'">
                </p>
                @error('tarif_per_jam') <p class="mt-1.5 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- ===================== PAKET BUILDER ===================== --}}
            <div x-show="tipeTarif === 'paket'" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <label class="block text-xs font-black text-gray-700 uppercase tracking-wider">
                            Daftar Paket / Tier <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-[11px] text-gray-400 font-medium mt-0.5">Buat 1–5 paket dengan harga dan benefit berbeda.</p>
                    </div>
                    <button type="button" @click="addPaket()"
                        x-show="pakets.length < 5"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Paket
                    </button>
                </div>
                @error('paket') <p class="mb-3 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror

                <div class="space-y-4">
                    <template x-for="(paket, index) in pakets" :key="paket.id">
                        <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5 relative group">
                            {{-- Remove button --}}
                            <button type="button" @click="removePaket(index)"
                                x-show="pakets.length > 1"
                                class="absolute top-4 right-4 w-7 h-7 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-500 rounded-xl flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            {{-- Tier badge --}}
                            <div class="flex items-center gap-2 mb-4">
                                <span class="w-6 h-6 bg-indigo-600 rounded-lg text-white text-[10px] font-black flex items-center justify-center" x-text="index + 1"></span>
                                <span class="text-xs font-black text-gray-600 uppercase tracking-wider">Paket</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Nama Paket <span class="text-rose-400">*</span></label>
                                    <input type="text" :name="`paket[${index}][nama_paket]`"
                                        x-model="paket.nama_paket"
                                        placeholder="Basic, Standard, Premium..."
                                        class="block w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-900 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Harga (Rp) <span class="text-rose-400">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Rp</span>
                                        <input type="number" :name="`paket[${index}][harga]`"
                                            x-model="paket.harga"
                                            min="1000" step="1000" placeholder="500000"
                                            class="block w-full rounded-xl border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-sm font-medium text-gray-900 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
                                    </div>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1.5">
                                        Deskripsi / Benefit Paket
                                        <span class="font-normal text-gray-400 normal-case">(opsional)</span>
                                    </label>
                                    <textarea :name="`paket[${index}][deskripsi]`"
                                        x-model="paket.deskripsi"
                                        rows="3" placeholder="Tulis benefit yang didapat pelanggan di paket ini: fitur, revisi, durasi garansi, dll..."
                                        class="block w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-900 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            {{-- ================ END PAKET BUILDER ================= --}}

            {{-- Foto Jasa --}}
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                    Foto Thumbnail Layanan
                </label>
                <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer"
                     onclick="document.getElementById('foto_jasa').click()">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-sm font-bold text-gray-500">Klik untuk unggah foto</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG — Maks 2MB</p>
                    <input id="foto_jasa" type="file" name="foto_jasa" class="hidden" accept="image/jpeg,image/png,image/jpg,image/webp">
                </div>
                @error('foto_jasa') <p class="mt-1.5 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <input id="is_aktif" name="is_aktif" type="checkbox" value="1"
                    {{ old('is_aktif', '1') == '1' ? 'checked' : '' }}
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <div>
                    <label for="is_aktif" class="text-sm font-bold text-gray-900 cursor-pointer">Aktifkan Layanan Segera</label>
                    <p class="text-[11px] text-gray-400 font-medium">Layanan langsung tampil di pencarian pelanggan setelah disimpan.</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('penyedia.jasa.index') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-sm shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@php
    $initialPakets = old('paket')
        ? collect(old('paket'))->values()->map(function($p, $i) { return array_merge($p, ['id' => $i]); })->values()->toArray()
        : [['id' => 0, 'nama_paket' => '', 'harga' => '', 'deskripsi' => '']];
    $initialNextId = old('paket') ? count(old('paket')) : 1;
@endphp

@push('scripts')
<script>
function jasaForm(initialTipe) {
    return {
        tipeTarif: initialTipe,
        pakets: @json($initialPakets),
        _nextId: {{ $initialNextId }},
        addPaket() {
            if (this.pakets.length >= 5) return;
            this.pakets.push({ id: this._nextId++, nama_paket: '', harga: '', deskripsi: '' });
        },
        removePaket(index) {
            if (this.pakets.length <= 1) return;
            this.pakets.splice(index, 1);
        }
    };
}
</script>
@endpush
