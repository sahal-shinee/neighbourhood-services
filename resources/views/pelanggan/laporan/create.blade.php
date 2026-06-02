@extends('layouts.app')
@section('title', 'Laporkan Penyedia')
@section('header', 'Laporkan Penyedia')
@section('subheader', 'Sampaikan keluhan atau pelanggaran kepada tim admin.')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Back --}}
    <a href="{{ url()->previous() }}"
       class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brand-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    {{-- Penyedia info --}}
    <div class="bg-white border border-gray-100 rounded-3xl p-5 flex items-center gap-4 mb-6 shadow-sm">
        <img src="{{ $penyedia->foto_profil_url }}" alt="{{ $penyedia->nama_lengkap }}"
             class="w-12 h-12 rounded-2xl object-cover flex-shrink-0">
        <div>
            <p class="font-black text-gray-900 text-sm">{{ $penyedia->nama_lengkap }}</p>
            <p class="text-xs font-semibold text-gray-400">Penyedia Jasa · ID #{{ $penyedia->id_pengguna }}</p>
        </div>
        <span class="ml-auto px-3 py-1 text-[10px] font-black bg-red-50 text-red-600 border border-red-100 rounded-full uppercase tracking-wider">Melaporkan</span>
    </div>

    {{-- Warning note --}}
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex gap-3 mb-6">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="text-xs font-semibold text-amber-700 leading-relaxed">
            Laporan palsu atau tidak berdasar dapat berdampak pada akun Anda. Pastikan informasi yang Anda sampaikan akurat dan didukung bukti.
        </p>
    </div>

    <form method="POST" action="{{ route('pelanggan.laporan.store') }}"
          enctype="multipart/form-data"
          class="bg-white border border-gray-100 rounded-3xl p-7 shadow-sm space-y-5">
        @csrf

        <input type="hidden" name="id_penyedia" value="{{ $penyedia->id_pengguna }}">

        {{-- Related order (optional) --}}
        @if($pesanan->isNotEmpty())
        <div>
            <label for="id_pesanan" class="block text-sm font-bold text-gray-700 mb-2">
                Pesanan Terkait <span class="font-normal text-gray-400">(opsional)</span>
            </label>
            <select id="id_pesanan" name="id_pesanan"
                class="block w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 focus:border-brand-500 focus:ring-0 outline-none">
                <option value="">-- Tidak ada pesanan spesifik --</option>
                @foreach($pesanan as $p)
                <option value="{{ $p->id_pesanan }}" {{ old('id_pesanan') == $p->id_pesanan ? 'selected' : '' }}>
                    #{{ $p->id_pesanan }} — {{ $p->jasa->nama_jasa ?? 'Jasa' }} · {{ \Carbon\Carbon::parse($p->tanggal_booking)->format('d M Y') }}
                </option>
                @endforeach
            </select>
            @error('id_pesanan') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
        </div>
        @else
        <input type="hidden" name="id_pesanan" value="">
        @endif

        {{-- Alasan --}}
        <div>
            <label for="alasan" class="block text-sm font-bold text-gray-700 mb-2">Kategori Pelanggaran</label>
            <select id="alasan" name="alasan" required
                class="block w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 focus:border-brand-500 focus:ring-0 outline-none">
                <option value="">-- Pilih alasan --</option>
                @foreach([
                    'Penipuan / Fraud',
                    'Kualitas Pekerjaan Buruk',
                    'Tidak Profesional',
                    'Tidak Tepat Waktu',
                    'Perilaku Kasar / Tidak Sopan',
                    'Meminta Bayaran di Luar Kesepakatan',
                    'Informasi Profil Menyesatkan',
                    'Lainnya',
                ] as $opt)
                <option value="{{ $opt }}" {{ old('alasan') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            @error('alasan') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
        </div>

        {{-- Detail --}}
        <div>
            <label for="detail_laporan" class="block text-sm font-bold text-gray-700 mb-2">
                Uraian Lengkap
                <span class="font-normal text-gray-400 text-xs ml-1">min. 20 karakter</span>
            </label>
            <textarea id="detail_laporan" name="detail_laporan" rows="5" required
                placeholder="Jelaskan secara rinci kejadian yang terjadi, kapan, dan dampaknya bagi Anda..."
                class="block w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 placeholder-gray-400 focus:border-brand-500 focus:ring-0 outline-none resize-none">{{ old('detail_laporan') }}</textarea>
            @error('detail_laporan') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
        </div>

        {{-- Bukti --}}
        <div>
            <label for="bukti_foto" class="block text-sm font-bold text-gray-700 mb-2">
                Bukti Foto <span class="font-normal text-gray-400">(opsional, maks. 3 MB)</span>
            </label>
            <label for="bukti_foto"
                   class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-brand-400 hover:bg-brand-50/30 transition-all duration-200 group">
                <svg class="w-8 h-8 text-gray-300 group-hover:text-brand-400 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs font-semibold text-gray-400 group-hover:text-brand-600 transition-colors" id="bukti-label">Klik untuk unggah gambar</span>
                <input id="bukti_foto" name="bukti_foto" type="file" accept="image/*" class="sr-only"
                       onchange="document.getElementById('bukti-label').textContent = this.files[0]?.name ?? 'Klik untuk unggah gambar'">
            </label>
            @error('bukti_foto') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
        </div>

        {{-- Submit --}}
        <div class="pt-2 flex gap-3">
            <a href="{{ url()->previous() }}"
               class="flex-1 text-center py-3.5 px-4 border-2 border-gray-200 rounded-2xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                Batal
            </a>
            <button type="submit"
                class="flex-1 py-3.5 px-4 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-red-500/20 hover:-translate-y-0.5 active:scale-[.98] transition-all">
                Kirim Laporan
            </button>
        </div>
    </form>
</div>
@endsection
