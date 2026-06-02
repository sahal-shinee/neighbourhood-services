@extends('layouts.app')
@section('title', 'Penyedia Dashboard')
@section('header', 'Analisis Kinerja Jasa')
@section('subheader', 'Pantau performa dan pesanan layanan Anda secara real-time.')

@section('content')

{{-- ===================== VERIFICATION STATUS BANNERS ===================== --}}
@php $user = auth()->user(); @endphp

@if($user->status_verifikasi === 'pending' && !empty($user->pesan_banding))
    <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-3xl p-6 flex items-start gap-5">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(251,191,36,0.15),transparent_60%)] pointer-events-none"></div>
        <div class="relative w-12 h-12 bg-amber-100 border border-amber-200 rounded-2xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600 animate-spin" style="animation-duration:3s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="relative flex-1">
            <h4 class="font-extrabold text-amber-900 text-base mb-1">Aju Banding Sedang Ditinjau Admin</h4>
            <p class="text-sm font-semibold text-amber-800 leading-relaxed">Pesan banding Anda telah dikirim dan sedang dalam antrian peninjauan oleh Admin. Harap bersabar — Anda akan dinotifikasi segera setelah keputusan diambil.</p>
            <div class="mt-3 p-3 bg-amber-100/60 rounded-xl border border-amber-200/60">
                <p class="text-xs font-bold text-amber-700 mb-1">Pesan banding Anda:</p>
                <p class="text-sm text-amber-900 font-medium italic">"{{ $user->pesan_banding }}"</p>
            </div>
        </div>
    </div>

@elseif($user->status_verifikasi === 'pending')
    <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-3xl p-6 flex items-start gap-5">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(99,102,241,0.1),transparent_60%)] pointer-events-none"></div>
        <div class="relative w-12 h-12 bg-blue-100 border border-blue-200 rounded-2xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div class="relative">
            <h4 class="font-extrabold text-blue-900 text-base mb-1">Menunggu Verifikasi Admin</h4>
            <p class="text-sm font-semibold text-blue-800 leading-relaxed">Akun Anda sedang ditinjau oleh Admin. Layanan Anda akan otomatis tampil di pencarian publik setelah verifikasi KTP disetujui.</p>
        </div>
    </div>

@elseif($user->status_verifikasi === 'ditolak')
    <div x-data="{ showForm: false }" class="mb-8">
        <div class="relative overflow-hidden bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200 rounded-3xl p-6 flex items-start gap-5">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(239,68,68,0.1),transparent_60%)] pointer-events-none"></div>
            <div class="relative w-12 h-12 bg-rose-100 border border-rose-200 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="relative flex-1">
                <h4 class="font-extrabold text-rose-900 text-base mb-1">Pengajuan Verifikasi Ditolak</h4>
                <p class="text-sm font-semibold text-rose-800 leading-relaxed mb-4">Pengajuan verifikasi KTP Anda tidak disetujui oleh Admin. Anda dapat mengajukan banding disertai alasan dan mengunggah foto KTP baru yang lebih jelas.</p>
                <button @click="showForm = !showForm"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-sm shadow-rose-300 transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3z"/></svg>
                    <span x-text="showForm ? 'Batalkan' : 'Ajukan Banding'"></span>
                </button>
            </div>
        </div>

        <div x-show="showForm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-4 bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
            <h4 class="font-bold text-gray-900 text-base mb-1">Formulir Aju Banding</h4>
            <p class="text-sm text-gray-500 font-medium mb-5">Jelaskan alasan banding Anda dan unggah foto KTP baru yang lebih jelas (opsional). Admin akan meninjau ulang permohonan Anda.</p>

            <form method="POST" action="{{ route('penyedia.profil.banding') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pengajuan Banding <span class="text-rose-500">*</span></label>
                    <textarea name="pesan_banding" rows="4" required
                        placeholder="Jelaskan mengapa Anda mengajukan banding dan apa yang sudah diperbaiki..."
                        class="w-full text-sm font-medium text-gray-800 bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-none transition-all placeholder-gray-400 hover:border-gray-300">{{ old('pesan_banding') }}</textarea>
                    @error('pesan_banding')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>@enderror
                </div>

                <div x-data="{ fileName: '' }">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Foto KTP Baru <span class="text-gray-400 font-medium">(Opsional)</span></label>
                    <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-300 transition-all group">
                        <div class="flex flex-col items-center justify-center text-center px-4" x-show="!fileName">
                            <svg class="w-8 h-8 text-gray-300 group-hover:text-indigo-400 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm font-semibold text-gray-500 group-hover:text-indigo-600 transition-colors">Klik untuk upload foto KTP</p>
                            <p class="text-xs text-gray-400 mt-1">JPG, JPEG, PNG — Maks. 3 MB</p>
                        </div>
                        <div x-show="fileName" class="flex items-center gap-3 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm font-bold" x-text="fileName"></span>
                        </div>
                        <input type="file" name="foto_ktp" accept="image/*" class="hidden" @change="fileName = $event.target.files[0]?.name || ''">
                    </label>
                    @error('foto_ktp')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-bold text-sm px-6 py-3 rounded-2xl shadow-sm shadow-indigo-200 transition-all hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Aju Banding
                    </button>
                    <button type="button" @click="showForm = false" class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-3 rounded-2xl hover:bg-gray-50 transition-all">Batalkan</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- ===================== STATS GRID ===================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    @php
        $statCards = [
            [
                'title' => 'Layanan Saya', 'value' => $stats['total_jasa'], 'suffix' => 'jasa aktif',
                'gradient' => 'from-indigo-500 to-blue-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            ],
            [
                'title' => 'Total Pesanan', 'value' => $stats['total_pesanan'], 'suffix' => 'pesanan masuk',
                'gradient' => 'from-emerald-500 to-teal-500',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
            ],
            [
                'title' => 'Rating Rata-rata', 'value' => number_format($stats['rating_rata_rata'], 1) . ' / 5.0', 'suffix' => 'penilaian',
                'gradient' => 'from-amber-400 to-orange-500',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
            ],
            [
                'title' => 'Pendapatan Bln Ini', 'value' => 'Rp ' . number_format($stats['pendapatan_bulan_ini'], 0, ',', '.'), 'suffix' => 'estimasi',
                'gradient' => 'from-blue-500 to-cyan-500',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ],
        ];
    @endphp

    @foreach($statCards as $i => $card)
    <div class="card-lift bg-white border border-gray-100 rounded-3xl p-6 shadow-sm group" style="animation: fadeInUp 0.5s {{ $i * 0.08 }}s cubic-bezier(0.16,1,0.3,1) both;">
        <div class="flex items-start justify-between mb-5">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br {{ $card['gradient'] }} flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $card['icon'] !!}</svg>
            </div>
            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg">{{ $card['suffix'] }}</span>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 tracking-tight mb-1 tabular-nums">{{ $card['value'] }}</p>
        <p class="text-xs font-semibold text-gray-500">{{ $card['title'] }}</p>
    </div>
    @endforeach
</div>

{{-- ===================== CHARTS + QUICK ACTIONS ===================== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-3xl p-6 shadow-sm card-lift">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">Statistik Pesanan Bulanan</h3>
                <p class="text-xs font-semibold text-gray-400 mt-0.5">Frekuensi pesanan masuk per bulan</p>
            </div>
            <span class="text-xs font-extrabold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100">Aktif</span>
        </div>
        <div class="h-72 relative"><canvas id="providerOrdersChart"></canvas></div>
    </div>

    <div class="bg-[#0f1117] rounded-3xl p-6 shadow-sm relative overflow-hidden flex flex-col justify-between card-lift">
        <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-500 rounded-full filter blur-3xl opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-500 rounded-full filter blur-2xl opacity-10 pointer-events-none"></div>
        <div class="relative">
            <h3 class="text-base font-bold text-white mb-1">Tindakan Cepat</h3>
            <p class="text-xs text-gray-500 mb-6 font-semibold">Kelola layanan & profil Anda</p>
            <ul class="space-y-3">
                <li>
                    <a href="{{ route('penyedia.jasa.create') }}" class="flex items-center bg-white/[0.05] hover:bg-white/[0.09] p-3.5 rounded-2xl border border-white/[0.07] hover:border-white/[0.14] transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/15 flex items-center justify-center mr-3.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div><span class="font-bold block text-sm text-white">Tambah Layanan</span><span class="text-xs text-gray-500 font-medium">Tawarkan keahlian baru</span></div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('penyedia.portofolio.index') }}" class="flex items-center bg-white/[0.05] hover:bg-white/[0.09] p-3.5 rounded-2xl border border-white/[0.07] hover:border-white/[0.14] transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/15 flex items-center justify-center mr-3.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div><span class="font-bold block text-sm text-white">Portofolio Kerja</span><span class="text-xs text-gray-500 font-medium">Upload hasil karya Anda</span></div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('penyedia.profil') }}" class="flex items-center bg-white/[0.05] hover:bg-white/[0.09] p-3.5 rounded-2xl border border-white/[0.07] hover:border-white/[0.14] transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/15 flex items-center justify-center mr-3.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div><span class="font-bold block text-sm text-white">Kelola Profil</span><span class="text-xs text-gray-500 font-medium">Update data diri & kontak</span></div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="relative mt-6 pt-4 border-t border-white/[0.06] text-center text-[10px] font-bold text-gray-600 uppercase tracking-widest">Penyedia Terverifikasi Neighbourhood</div>
    </div>
</div>

{{-- ===================== RECENT ORDERS ===================== --}}
<div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-base font-bold text-gray-900">Pesanan Masuk Terbaru</h3>
            <p class="text-xs font-semibold text-gray-400 mt-0.5">Daftar transaksi dan permintaan jasa terbaru</p>
        </div>
        @php
            $recent_orders = auth()->user()->jasaSebagaiPenyedia()
                ->with(['pesanan' => fn($q) => $q->latest()->take(5)])
                ->get()->pluck('pesanan')->flatten()
                ->sortByDesc('created_at')->take(5);
        @endphp
        @if($recent_orders->count() > 0)
            <a href="{{ route('penyedia.pesanan.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl border border-indigo-100 transition-all">Kelola Semua</a>
        @endif
    </div>
    <div class="space-y-3">
        @forelse($recent_orders as $order)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 hover:bg-indigo-50/40 rounded-2xl border border-gray-100 hover:border-indigo-100 transition-all gap-3">
                <div class="flex items-center gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-sm font-extrabold text-indigo-600">{{ strtoupper(substr($order->pelanggan->nama_lengkap, 0, 2)) }}</div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $order->pelanggan->nama_lengkap }}</p>
                        <p class="text-xs font-semibold text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($order->tanggal_booking)->format('d M Y') }} · {{ \Carbon\Carbon::parse($order->jam_mulai)->format('H:i') }} WIB</p>
                    </div>
                </div>
                <x-badge :variant="$order->status_variant">{{ $order->status_label }}</x-badge>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <p class="text-gray-500 font-semibold text-sm">Belum ada pesanan jasa masuk saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('providerOrdersChart').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0, 'rgba(16,185,129,0.7)');
    grad.addColorStop(1, 'rgba(16,185,129,0.08)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chart_labels),
            datasets: [{
                label: 'Pesanan',
                data: @json($chart_values),
                backgroundColor: grad,
                borderRadius: 10,
                borderSkipped: false,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(243,244,246,0.8)' }, ticks: { font: { weight: '700', size: 11 }, color: '#9ca3af' } },
                x: { grid: { display: false }, ticks: { font: { weight: '700', size: 11 }, color: '#9ca3af' } }
            }
        }
    });
});
</script>
@endpush

@endsection
