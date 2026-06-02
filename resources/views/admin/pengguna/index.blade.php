@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('header', 'Data Pengguna')

@section('content')

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 mb-6">
    <form action="{{ route('admin.pengguna.index') }}" method="GET">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                </span>
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
            </div>
            <div class="relative sm:w-48">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <select name="peran" onchange="this.form.submit()"
                    class="w-full pl-10 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all appearance-none cursor-pointer">
                    <option value="">Semua Peran</option>
                    <option value="pelanggan" {{ request('peran') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                    <option value="penyedia"  {{ request('peran') == 'penyedia'  ? 'selected' : '' }}>Penyedia Jasa</option>
                    <option value="admin"     {{ request('peran') == 'admin'     ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200 active:scale-[0.97]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                Cari
            </button>
            @if(request()->hasAny(['search', 'peran']))
                <a href="{{ route('admin.pengguna.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900">
            Daftar Pengguna
            @if(request()->hasAny(['search', 'peran']))
                <span class="ml-2 text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Filter aktif</span>
            @endif
        </h3>
        <span class="text-xs font-bold text-gray-400">{{ $pengguna->total() }} total pengguna</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50/70 border-b border-gray-100">
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Pengguna</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Kontak</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Peran</th>
                    <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengguna as $i => $user)
                    <tr class="group hover:bg-indigo-50/30 transition-colors duration-150"
                        style="animation: fadeInUp 0.4s {{ $i * 0.04 }}s cubic-bezier(0.16,1,0.3,1) both;">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="relative flex-shrink-0">
                                    <img src="{{ $user->foto_profil_url }}" alt=""
                                         class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-sm">
                                    {{-- Colored dot per role --}}
                                    @php
                                        $dotColor = $user->peran === 'admin' ? 'bg-violet-500' : ($user->peran === 'penyedia' ? 'bg-blue-500' : 'bg-emerald-500');
                                    @endphp
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 {{ $dotColor }} rounded-full border-2 border-white"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $user->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5">Terdaftar {{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-800">{{ $user->email }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $user->no_telepon ?: '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleBadge = match($user->peran) {
                                    'admin'    => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200/60',
                                    'penyedia' => 'bg-blue-50   text-blue-700   ring-1 ring-blue-200/60',
                                    default    => 'bg-gray-100  text-gray-600   ring-1 ring-gray-200/60',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $roleBadge }}">
                                {{ ucfirst($user->peran) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status_verifikasi == 'diverifikasi')
                                <x-badge variant="success">Diverifikasi</x-badge>
                            @elseif($user->status_verifikasi == 'pending')
                                <x-badge variant="warning">Pending</x-badge>
                            @else
                                <x-badge variant="danger">Ditolak</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(!$user->isAdmin())
                                <div x-data="{ confirm: false }">
                                    <button x-show="!confirm" @click="confirm = true"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl border border-rose-100 transition-all hover:scale-105 active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                    <div x-show="confirm" x-transition class="flex items-center gap-2 justify-end">
                                        <span class="text-xs font-bold text-gray-600">Yakin?</span>
                                        <form action="{{ route('admin.pengguna.destroy', $user->id_pengguna) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-all">Ya, Hapus</button>
                                        </form>
                                        <button @click="confirm = false" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-all">Batal</button>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-300 font-semibold">Protected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-700">Tidak ada pengguna ditemukan</p>
                                <p class="text-xs text-gray-400 font-medium mt-1">Coba ubah filter pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pengguna->hasPages())
        <div class="px-6 pb-5">
            {{ $pengguna->links('components.pagination') }}
        </div>
    @endif
</div>

@endsection
