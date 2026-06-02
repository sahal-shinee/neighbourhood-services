@extends('layouts.app')
@section('title', 'Data Pesanan')
@section('header', 'Seluruh Transaksi Pesanan')

@section('content')

{{-- Status Filter Tabs --}}
<div class="mb-6">
    <div class="flex flex-wrap items-center gap-2">
        @php
            $statusTabs = [
                ''           => ['label' => 'Semua',    'color' => 'gray'],
                'menunggu'   => ['label' => 'Menunggu', 'color' => 'amber'],
                'disetujui'  => ['label' => 'Disetujui','color' => 'blue'],
                'selesai'    => ['label' => 'Selesai',  'color' => 'emerald'],
                'dibatalkan' => ['label' => 'Dibatalkan','color' => 'rose'],
            ];
            $currentStatus = request('status', '');
        @endphp
        @foreach($statusTabs as $val => $tab)
            @php
                $isActive = $currentStatus === $val;
                $activeClass = match($tab['color']) {
                    'amber'   => 'bg-amber-500 text-white shadow-sm shadow-amber-200',
                    'blue'    => 'bg-blue-500 text-white shadow-sm shadow-blue-200',
                    'emerald' => 'bg-emerald-500 text-white shadow-sm shadow-emerald-200',
                    'rose'    => 'bg-rose-500 text-white shadow-sm shadow-rose-200',
                    default   => 'bg-gray-800 text-white shadow-sm',
                };
                $inactiveClass = 'bg-white text-gray-600 border border-gray-200 hover:border-gray-300 hover:bg-gray-50';
            @endphp
            <a href="{{ route('admin.pesanan', $val ? ['status' => $val] : []) }}"
               class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $isActive ? $activeClass : $inactiveClass }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900">
            @if($currentStatus)
                Pesanan dengan status <span class="text-indigo-600">{{ ucfirst($currentStatus) }}</span>
            @else
                Semua Transaksi
            @endif
        </h3>
        <span class="text-xs font-bold text-gray-400">{{ $pesanan->total() }} total data</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50/70 border-b border-gray-100">
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">ID</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Jadwal</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Layanan</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Pelanggan</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Penyedia</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pesanan as $i => $p)
                    @php
                        $leftBorder = match($p->status_pesanan) {
                            'menunggu'   => 'border-l-2 border-l-amber-400',
                            'disetujui'  => 'border-l-2 border-l-blue-400',
                            'selesai'    => 'border-l-2 border-l-emerald-400',
                            'dibatalkan' => 'border-l-2 border-l-rose-400',
                            default      => 'border-l-2 border-l-gray-200',
                        };
                    @endphp
                    <tr class="group hover:bg-indigo-50/20 transition-colors duration-150 {{ $leftBorder }}"
                        style="animation: fadeInUp 0.4s {{ $i * 0.03 }}s cubic-bezier(0.16,1,0.3,1) both;">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-black text-gray-500 font-mono">#{{ str_pad($p->id_pesanan, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($p->tanggal_booking)->translatedFormat('d M Y') }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ \Carbon\Carbon::parse($p->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($p->jam_selesai)->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900 line-clamp-1 max-w-[180px]">{{ $p->jasa->nama_jasa }}</p>
                            <p class="text-xs text-indigo-600 font-semibold mt-0.5">{{ $p->jasa->tarif_label }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <img src="{{ $p->pelanggan->foto_profil_url }}" class="w-6 h-6 rounded-full object-cover ring-1 ring-gray-200 flex-shrink-0" alt="">
                                <span class="text-sm font-semibold text-gray-700">{{ $p->pelanggan->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <img src="{{ $p->jasa->penyedia->foto_profil_url }}" class="w-6 h-6 rounded-full object-cover ring-1 ring-gray-200 flex-shrink-0" alt="">
                                <span class="text-sm font-semibold text-gray-700">{{ $p->jasa->penyedia->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge :variant="$p->status_variant">{{ $p->status_label }}</x-badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-700">Belum ada transaksi</p>
                                <p class="text-xs text-gray-400 font-medium mt-1">Tidak ada pesanan dengan status terpilih.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pesanan->hasPages())
        <div class="px-6 pb-5">
            {{ $pesanan->links('components.pagination') }}
        </div>
    @endif
</div>

@endsection
