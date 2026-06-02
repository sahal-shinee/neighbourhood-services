@extends('layouts.app')
@section('title', 'Laporan Pengguna')
@section('header', 'Laporan Pengguna')
@section('subheader', 'Tinjau dan tindaklanjuti laporan pelanggaran dari pelanggan terhadap penyedia jasa.')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
<div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Filter tabs --}}
<div class="flex gap-2 mb-6 flex-wrap">
    @foreach([''=>'Semua','baru'=>'Baru','ditinjau'=>'Ditinjau','ditindaklanjuti'=>'Ditindaklanjuti','ditolak'=>'Ditolak'] as $val => $lbl)
    <a href="{{ route('admin.laporan.index', ['status' => $val ?: null]) }}"
       class="px-4 py-2 rounded-xl text-xs font-bold border transition-all {{ request('status') == $val ? 'bg-brand-600 text-white border-brand-600' : 'bg-white text-gray-500 border-gray-200 hover:border-brand-300 hover:text-brand-600' }}">
        {{ $lbl }}
    </a>
    @endforeach
</div>

<div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">
    @if($laporan->isEmpty())
    <div class="flex flex-col items-center justify-center py-24 text-gray-400">
        <svg class="w-12 h-12 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="font-bold text-sm">Belum ada laporan</p>
        <p class="text-xs mt-1">Laporan dari pelanggan akan muncul di sini.</p>
    </div>
    @else
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left text-[10px] font-black text-gray-400 uppercase tracking-wider px-6 py-4">Pelapor</th>
                <th class="text-left text-[10px] font-black text-gray-400 uppercase tracking-wider px-6 py-4">Penyedia Dilaporkan</th>
                <th class="text-left text-[10px] font-black text-gray-400 uppercase tracking-wider px-6 py-4">Alasan</th>
                <th class="text-left text-[10px] font-black text-gray-400 uppercase tracking-wider px-6 py-4">Status</th>
                <th class="text-left text-[10px] font-black text-gray-400 uppercase tracking-wider px-6 py-4">Tanggal</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($laporan as $l)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $l->pelapor->foto_profil_url }}" class="w-8 h-8 rounded-xl object-cover flex-shrink-0">
                        <div>
                            <p class="font-bold text-gray-900 text-xs">{{ $l->pelapor->nama_lengkap }}</p>
                            <p class="text-[10px] text-gray-400">{{ $l->pelapor->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ $l->penyedia->foto_profil_url }}" class="w-8 h-8 rounded-xl object-cover flex-shrink-0">
                        <div>
                            <p class="font-bold text-gray-900 text-xs">{{ $l->penyedia->nama_lengkap }}</p>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold {{ $l->penyedia->is_aktif ? 'text-emerald-600' : 'text-red-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $l->penyedia->is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-semibold text-gray-700">{{ $l->alasan }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-full border {{ $l->statusColor() }}">
                        {{ $l->statusLabel() }}
                    </span>
                </td>
                <td class="px-6 py-4 text-[10px] text-gray-400 font-semibold whitespace-nowrap">
                    {{ $l->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.laporan.show', $l->id_laporan) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-50 text-brand-600 border border-brand-100 rounded-xl text-xs font-bold hover:bg-brand-600 hover:text-white transition-all">
                        Tinjau
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($laporan->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $laporan->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
