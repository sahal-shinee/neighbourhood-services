@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('header', 'Ringkasan Sistem & Analisis')
@section('subheader', 'Pantau performa platform Neighbourhood Services secara real-time.')

@section('content')

{{-- ===================== STAT CARDS ===================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    @php
        $cards = [
            [
                'title' => 'Total Pengguna', 'value' => $stats['total_pengguna'], 'suffix' => 'terdaftar', 'delay' => '0s',
                'gradient' => 'from-indigo-500 to-blue-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
            ],
            [
                'title' => 'Penyedia Aktif', 'value' => $stats['penyedia_aktif'], 'suffix' => 'terverifikasi', 'delay' => '0.08s',
                'gradient' => 'from-emerald-500 to-teal-500',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            ],
            [
                'title' => 'Pesanan Hari Ini', 'value' => $stats['pesanan_hari_ini'], 'suffix' => 'transaksi', 'delay' => '0.16s',
                'gradient' => 'from-blue-500 to-cyan-500',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
            ],
            [
                'title' => 'Pending Verifikasi', 'value' => $stats['pending_verifikasi'], 'suffix' => 'menunggu', 'delay' => '0.24s',
                'gradient' => 'from-amber-400 to-orange-500',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="card-lift bg-white border border-gray-100 rounded-3xl p-6 shadow-sm group" style="animation: fadeInUp 0.5s {{ $card['delay'] }} cubic-bezier(0.16,1,0.3,1) both;">
        <div class="flex items-start justify-between mb-5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $card['gradient'] }} flex items-center justify-center shadow-md shadow-{{ explode(' ', $card['gradient'])[0] }}/20 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $card['icon'] !!}</svg>
            </div>
            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg">{{ $card['suffix'] }}</span>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1 tabular-nums">{{ $card['value'] }}</p>
        <p class="text-xs font-semibold text-gray-500">{{ $card['title'] }}</p>
    </div>
    @endforeach
</div>

{{-- ===================== CHARTS ===================== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Line Chart --}}
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-3xl p-6 shadow-sm card-lift">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">Tren Pertumbuhan Transaksi</h3>
                <p class="text-xs font-semibold text-gray-400 mt-0.5">Akumulasi pesanan bulanan platform</p>
            </div>
            <span class="text-xs font-extrabold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100">Real-time</span>
        </div>
        <div class="h-72 relative"><canvas id="transactionChart"></canvas></div>
    </div>

    {{-- Doughnut Chart --}}
    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm card-lift flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-gray-900 mb-0.5">Distribusi Pengguna</h3>
            <p class="text-xs font-semibold text-gray-400 mb-5">Proporsi peran akun terdaftar</p>
        </div>
        <div class="h-52 relative flex items-center justify-center">
            <canvas id="userRolesChart"></canvas>
        </div>
        <div class="mt-5 grid grid-cols-3 gap-2 text-center border-t border-gray-100 pt-4">
            <div>
                <div class="w-2 h-2 rounded-full bg-indigo-500 mx-auto mb-1"></div>
                <span class="block text-[10px] font-extrabold text-gray-400 uppercase">Pelanggan</span>
                <span class="text-sm font-black text-indigo-600">{{ $role_stats['pelanggan'] }}</span>
            </div>
            <div>
                <div class="w-2 h-2 rounded-full bg-emerald-500 mx-auto mb-1"></div>
                <span class="block text-[10px] font-extrabold text-gray-400 uppercase">Penyedia</span>
                <span class="text-sm font-black text-emerald-600">{{ $role_stats['penyedia'] }}</span>
            </div>
            <div>
                <div class="w-2 h-2 rounded-full bg-rose-500 mx-auto mb-1"></div>
                <span class="block text-[10px] font-extrabold text-gray-400 uppercase">Admin</span>
                <span class="text-sm font-black text-rose-500">{{ $role_stats['admin'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ===================== RECENT ACTIVITY + QUICK ACTIONS ===================== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent Users Table --}}
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">Pendaftar Terbaru</h3>
                <p class="text-xs font-semibold text-gray-400 mt-0.5">Pengguna yang baru bergabung dengan platform</p>
            </div>
            <a href="{{ route('admin.pengguna.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl border border-indigo-100 transition-all">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto -mx-2">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-2 pb-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Nama & Email</th>
                        <th class="px-2 pb-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Peran</th>
                        <th class="px-2 pb-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-2 pb-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recent_activity as $user)
                        <tr class="hover:bg-gray-50/60 transition-colors group">
                            <td class="py-3.5 px-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-100 to-blue-100 flex items-center justify-center text-xs font-extrabold text-indigo-600 flex-shrink-0">{{ strtoupper(substr($user->nama_lengkap, 0, 2)) }}</div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $user->nama_lengkap }}</p>
                                        <p class="text-xs font-medium text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-extrabold capitalize bg-gray-100 text-gray-600 uppercase tracking-wide">{{ $user->peran }}</span>
                            </td>
                            <td class="py-3.5 px-2">
                                @if($user->status_verifikasi === 'diverifikasi')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wide">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Aktif
                                    </span>
                                @elseif($user->status_verifikasi === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wide">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-100 uppercase tracking-wide">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-2 text-xs text-gray-400 font-semibold whitespace-nowrap">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400 font-semibold">Belum ada pengguna baru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-[#0f1117] rounded-3xl p-6 shadow-sm relative overflow-hidden flex flex-col justify-between">
        <div class="absolute top-0 right-0 w-44 h-44 bg-indigo-500 rounded-full filter blur-3xl opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-28 h-28 bg-blue-500 rounded-full filter blur-2xl opacity-10 pointer-events-none"></div>
        <div class="relative">
            <h3 class="text-base font-bold text-white mb-1">Tindakan Cepat</h3>
            <p class="text-xs text-gray-500 mb-5 font-semibold">Kelola operasional inti platform</p>
            <ul class="space-y-3">
                @if($stats['pending_verifikasi'] > 0)
                <li>
                    <a href="{{ route('admin.verifikasi') }}" class="flex items-center justify-between bg-amber-500/10 hover:bg-amber-500/20 p-4 rounded-2xl border border-amber-500/20 hover:border-amber-500/40 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <span class="font-bold block text-sm text-white">Verifikasi Penyedia</span>
                                <span class="text-xs text-gray-500 font-medium">Ada pendaftar baru tertunda</span>
                            </div>
                        </div>
                        <span class="bg-red-500 text-white text-xs font-black px-2.5 py-1 rounded-lg shadow-sm">{{ $stats['pending_verifikasi'] }}</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('admin.pengguna.index') }}" class="flex items-center bg-white/[0.05] hover:bg-white/[0.09] p-4 rounded-2xl border border-white/[0.07] hover:border-white/[0.14] transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center mr-3.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div><span class="font-bold block text-sm text-white">Kelola Pengguna</span><span class="text-xs text-gray-500 font-medium">Lihat semua akun platform</span></div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kategori.index') }}" class="flex items-center bg-white/[0.05] hover:bg-white/[0.09] p-4 rounded-2xl border border-white/[0.07] hover:border-white/[0.14] transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center mr-3.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div><span class="font-bold block text-sm text-white">Kelola Kategori</span><span class="text-xs text-gray-500 font-medium">Ubah & tambah kategori jasa</span></div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pesanan') }}" class="flex items-center bg-white/[0.05] hover:bg-white/[0.09] p-4 rounded-2xl border border-white/[0.07] hover:border-white/[0.14] transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center mr-3.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div><span class="font-bold block text-sm text-white">Daftar Pesanan</span><span class="text-xs text-gray-500 font-medium">Semua transaksi platform</span></div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="relative mt-6 pt-4 border-t border-white/[0.06] text-center text-[10px] font-bold text-gray-600 uppercase tracking-widest">Administrator Neighbourhood Services</div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxTx = document.getElementById('transactionChart').getContext('2d');
    const gradTx = ctxTx.createLinearGradient(0, 0, 0, 280);
    gradTx.addColorStop(0, 'rgba(99,102,241,0.35)');
    gradTx.addColorStop(1, 'rgba(99,102,241,0.0)');
    new Chart(ctxTx, {
        type: 'line',
        data: {
            labels: @json($chart_labels),
            datasets: [{
                label: 'Pesanan',
                data: @json($chart_values),
                borderColor: '#6366f1',
                borderWidth: 3,
                backgroundColor: gradTx,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointBorderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(243,244,246,0.8)' }, ticks: { font: { weight: '700', size: 11 }, color: '#9ca3af' } },
                x: { grid: { display: false }, ticks: { font: { weight: '700', size: 11 }, color: '#9ca3af' } }
            }
        }
    });

    const ctxR = document.getElementById('userRolesChart').getContext('2d');
    new Chart(ctxR, {
        type: 'doughnut',
        data: {
            labels: ['Pelanggan', 'Penyedia', 'Admin'],
            datasets: [{ data: [{{ $role_stats['pelanggan'] }}, {{ $role_stats['penyedia'] }}, {{ $role_stats['admin'] }}], backgroundColor: ['#6366f1', '#10b981', '#f43f5e'], borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { display: false } } }
    });
});
</script>
@endpush

@endsection
