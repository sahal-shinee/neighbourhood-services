@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('header', 'Data Pengguna')

@section('content')

<div x-data="{ openAdd: {{ old('_form') === 'tambah_admin' ? 'true' : 'false' }}, showPass: false, showPassConfirm: false }"
     @keydown.escape.window="openAdd = false">

{{-- Action Bar --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <p class="text-sm text-gray-500 font-medium">Kelola semua akun pengguna yang terdaftar di platform.</p>
    <button type="button" @click="openAdd = true"
        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200/70 transition-all hover:-translate-y-0.5 active:scale-[0.97] whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Admin
    </button>
</div>

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

{{-- ===================== Modal Tambah Admin ===================== --}}
<div x-show="openAdd" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
     x-transition.opacity>

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="openAdd = false"></div>

    {{-- Panel --}}
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto custom-scroll"
         x-show="openAdd"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        {{-- Header --}}
        <div class="relative bg-gradient-to-br from-violet-600 to-indigo-600 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-white leading-tight">Tambah Admin Baru</h3>
                    <p class="text-xs text-indigo-100 font-medium mt-0.5">Akun langsung aktif &amp; terverifikasi</p>
                </div>
            </div>
            <button type="button" @click="openAdd = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.pengguna.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_form" value="tambah_admin">

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                    class="w-full px-4 py-2.5 bg-gray-50 border @error('nama_lengkap') border-rose-300 @else border-gray-200 @enderror rounded-xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all"
                    placeholder="Masukkan nama lengkap">
                @error('nama_lengkap') <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2.5 bg-gray-50 border @error('email') border-rose-300 @else border-gray-200 @enderror rounded-xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all"
                    placeholder="contoh@email.com">
                @error('email') <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- No. Telepon (opsional) --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">No. Telepon <span class="text-gray-400 font-medium">(opsional)</span></label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                    class="w-full px-4 py-2.5 bg-gray-50 border @error('no_telepon') border-rose-300 @else border-gray-200 @enderror rounded-xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all"
                    placeholder="08xxxxxxxxxx">
                @error('no_telepon') <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Password <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" name="password" required
                        class="w-full pl-4 pr-11 py-2.5 bg-gray-50 border @error('password') border-rose-300 @else border-gray-200 @enderror rounded-xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all"
                        placeholder="Minimal 8 karakter">
                    <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg x-show="!showPass" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPass" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password') <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Password <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input :type="showPassConfirm ? 'text' : 'password'" name="password_confirmation" required
                        class="w-full pl-4 pr-11 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all"
                        placeholder="Ulangi password">
                    <button type="button" @click="showPassConfirm = !showPassConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg x-show="!showPassConfirm" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPassConfirm" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-3 pt-2">
                <button type="button" @click="openAdd = false"
                    class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-all active:scale-[0.98]">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200/70 transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Admin
                </button>
            </div>
        </form>
    </div>
</div>

</div>{{-- /x-data wrapper --}}

@endsection
