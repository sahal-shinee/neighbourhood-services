@extends('layouts.app')
@section('title', 'Detail Laporan #' . $laporan->id_laporan)
@section('header', 'Detail Laporan')
@section('subheader', 'Tinjau, perbarui status, dan ambil tindakan terhadap laporan ini.')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.laporan.index') }}"
           class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brand-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Laporan
        </a>
        <form method="POST" action="{{ route('admin.laporan.destroy', $laporan->id_laporan) }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                onclick="return confirm('Hapus laporan ini secara permanen? Tindakan ini tidak dapat dibatalkan.')"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-red-600 border border-red-200 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus Laporan
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Report detail --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-7 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-black text-gray-900">Isi Laporan #{{ $laporan->id_laporan }}</h2>
                    <span class="px-3 py-1.5 text-[10px] font-black rounded-full border {{ $laporan->statusColor() }}">
                        {{ $laporan->statusLabel() }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Alasan Pelanggaran</p>
                        <p class="text-sm font-bold text-red-600 bg-red-50 border border-red-100 px-3 py-2 rounded-xl inline-block">{{ $laporan->alasan }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Uraian Lengkap</p>
                        <div class="bg-gray-50 rounded-2xl p-4 text-sm text-gray-700 font-medium leading-relaxed border border-gray-100">
                            {{ $laporan->detail_laporan }}
                        </div>
                    </div>

                    @if($laporan->bukti_foto)
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Bukti Foto</p>
                        <a href="{{ Storage::url($laporan->bukti_foto) }}" target="_blank">
                            <img src="{{ Storage::url($laporan->bukti_foto) }}" alt="Bukti"
                                 class="rounded-2xl border border-gray-100 max-h-64 object-cover hover:opacity-90 transition-opacity cursor-pointer">
                        </a>
                    </div>
                    @endif

                    @if($laporan->pesanan)
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Pesanan Terkait</p>
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 text-sm">
                            <p class="font-bold text-gray-900">Pesanan #{{ $laporan->pesanan->id_pesanan }}</p>
                            <p class="text-gray-500 font-medium text-xs mt-1">{{ $laporan->pesanan->jasa->nama_jasa ?? '-' }} · {{ \Carbon\Carbon::parse($laporan->pesanan->tanggal_booking)->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Update status form --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-7 shadow-sm">
                <h3 class="text-sm font-black text-gray-900 mb-5">Perbarui Status Laporan</h3>
                <form method="POST" action="{{ route('admin.laporan.status', $laporan->id_laporan) }}">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">Status</label>
                            <select name="status" class="block w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 focus:border-brand-500 focus:ring-0 outline-none">
                                @foreach(['baru'=>'Baru','ditinjau'=>'Ditinjau','ditindaklanjuti'=>'Ditindaklanjuti','ditolak'=>'Ditolak'] as $val => $lbl)
                                <option value="{{ $val }}" {{ $laporan->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">Catatan Admin <span class="font-normal text-gray-400">(opsional)</span></label>
                            <textarea name="catatan_admin" rows="3"
                                placeholder="Tambahkan catatan tindakan yang diambil..."
                                class="block w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 placeholder-gray-400 focus:border-brand-500 focus:ring-0 outline-none resize-none">{{ $laporan->catatan_admin }}</textarea>
                        </div>
                        <button type="submit"
                            class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-brand-500/20 hover:-translate-y-0.5 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar: parties + action --}}
        <div class="space-y-5">

            {{-- Pelapor --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-3">Pelapor</p>
                <div class="flex items-center gap-3">
                    <img src="{{ $laporan->pelapor->foto_profil_url }}" class="w-10 h-10 rounded-xl object-cover">
                    <div>
                        <p class="font-black text-gray-900 text-sm">{{ $laporan->pelapor->nama_lengkap }}</p>
                        <p class="text-[10px] text-gray-400">{{ $laporan->pelapor->email }}</p>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-3">Dilaporkan pada {{ $laporan->created_at->format('d M Y, H:i') }}</p>
            </div>

            {{-- Penyedia --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-3">Penyedia Dilaporkan</p>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $laporan->penyedia->foto_profil_url }}" class="w-10 h-10 rounded-xl object-cover">
                    <div>
                        <p class="font-black text-gray-900 text-sm">{{ $laporan->penyedia->nama_lengkap }}</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold {{ $laporan->penyedia->is_aktif ? 'text-emerald-600' : 'text-red-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $laporan->penyedia->is_aktif ? 'Akun Aktif' : 'Akun Nonaktif' }}
                        </span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 font-semibold mb-1">Total laporan terhadap akun ini:</p>
                <p class="text-2xl font-black text-gray-900 mb-4">{{ $laporan->penyedia->laporan()->count() }}</p>

                {{-- Toggle active/inactive --}}
                <form method="POST" action="{{ route('admin.penyedia.toggle-aktif', $laporan->penyedia->id_pengguna) }}">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('{{ $laporan->penyedia->is_aktif ? 'Nonaktifkan akun penyedia ini? Mereka tidak akan bisa login.' : 'Aktifkan kembali akun penyedia ini?' }}')"
                        class="w-full py-2.5 rounded-2xl text-xs font-black border-2 transition-all
                            {{ $laporan->penyedia->is_aktif
                                ? 'border-red-200 text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600'
                                : 'border-emerald-200 text-emerald-600 hover:bg-emerald-600 hover:text-white hover:border-emerald-600' }}">
                        {{ $laporan->penyedia->is_aktif ? 'Nonaktifkan Penyedia Ini' : 'Aktifkan Kembali Penyedia' }}
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
