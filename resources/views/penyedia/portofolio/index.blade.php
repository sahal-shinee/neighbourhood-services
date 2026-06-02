@extends('layouts.app')
@section('title', 'Portofolio Saya')
@section('header', 'Karya & Portofolio')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-sm font-medium text-gray-500">Tampilkan hasil kerja terbaik Anda untuk meyakinkan pelanggan.</p>
    <button x-data="" x-on:click="$dispatch('open-modal', 'add-portofolio')"
        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm shadow-indigo-200 transition-all hover:-translate-y-0.5 active:scale-[0.97]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Portofolio
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($portofolio as $i => $p)
        <div class="group relative rounded-2xl overflow-hidden shadow-[0_2px_12px_rgba(0,0,0,0.06)] hover:shadow-[0_15px_40px_rgba(0,0,0,0.12)] transition-all duration-400 h-64"
             style="animation: fadeInUp 0.4s {{ $i * 0.07 }}s cubic-bezier(0.16,1,0.3,1) both;">

            <img src="{{ Storage::disk('public')->url($p->foto_proyek) }}" alt="{{ $p->judul_proyek }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

            {{-- Gradient always visible at bottom --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>

            {{-- Title always visible --}}
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <h3 class="text-white font-bold text-sm leading-snug">{{ $p->judul_proyek }}</h3>
                @if($p->deskripsi_proyek)
                    <p class="text-gray-300 text-xs mt-1 line-clamp-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ $p->deskripsi_proyek }}</p>
                @endif
            </div>

            {{-- Hover overlay with actions --}}
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-start justify-end p-3">
                {{-- Delete button --}}
                <div x-data="{ confirm: false }" class="flex gap-2">
                    <button x-show="!confirm" @click.stop="confirm = true"
                        class="bg-rose-500/80 hover:bg-rose-600 backdrop-blur-sm text-white p-2 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    <div x-show="confirm" x-transition class="flex gap-1 items-center">
                        <form action="{{ route('penyedia.portofolio.destroy', $p->id_portofolio) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-rose-600 text-white text-[10px] font-black px-2.5 py-1.5 rounded-lg backdrop-blur-sm">Hapus</button>
                        </form>
                        <button @click.stop="confirm = false" class="bg-white/80 text-gray-800 text-[10px] font-black px-2.5 py-1.5 rounded-lg backdrop-blur-sm">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-20 h-20 mx-auto bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mb-5"
                     style="animation: float-slow 5s ease-in-out infinite;">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">Belum ada portofolio</h3>
                <p class="text-sm text-gray-400 font-medium mt-1.5 max-w-sm mx-auto leading-relaxed">Pelanggan cenderung memilih penyedia dengan bukti hasil kerja nyata. Upload foto karya terbaikmu!</p>
                <button x-data="" x-on:click="$dispatch('open-modal', 'add-portofolio')"
                    class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Upload Sekarang
                </button>
            </div>
        </div>
    @endforelse
</div>

{{-- Modal Tambah --}}
<x-modal name="add-portofolio" maxWidth="md">
    <div class="p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center border border-indigo-100">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h2 class="text-base font-bold text-gray-900">Tambah Portofolio Baru</h2>
        </div>

        <form action="{{ route('penyedia.portofolio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Judul/Nama Proyek <span class="text-rose-500">*</span></label>
                <input type="text" name="judul_proyek" required placeholder="cth: Perbaikan AC Rusak di Kantor X"
                    class="block w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm font-medium text-gray-800 transition-all duration-200 placeholder-gray-400 hover:bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi_proyek" rows="3" placeholder="Tuliskan detail pekerjaan atau teknologi yang digunakan..."
                    class="block w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm font-medium text-gray-800 transition-all duration-200 placeholder-gray-400 hover:bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 focus:outline-none resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Foto Hasil Kerja <span class="text-rose-500">*</span></label>
                <div class="mt-1 relative border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-indigo-400 hover:bg-indigo-50/10 transition-all">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-xs text-gray-500 font-bold mb-1">Klik atau drag file ke sini</p>
                    <p class="text-[10px] text-gray-400 font-medium">JPG, PNG, WEBP • Maks 2MB</p>
                    <input type="file" name="foto_proyek" accept="image/jpeg,image/png,image/webp" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200">
                    Upload Portofolio
                </button>
            </div>
        </form>
    </div>
</x-modal>

@push('styles')
<style>
    @keyframes float-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .transition-400 { transition-duration: 400ms; }
</style>
@endpush

@endsection
