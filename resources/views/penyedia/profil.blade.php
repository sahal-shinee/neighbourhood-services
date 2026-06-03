@extends('layouts.app')
@section('title', 'Profil Penyedia')
@section('header', 'Profil Saya')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.04)] overflow-hidden">

        {{-- Card Header Gradient --}}
        <div class="h-28 bg-gradient-to-r from-indigo-600 to-indigo-700 relative flex items-end px-8 pb-6">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12)_0%,transparent_60%)]"></div>
            <div class="relative text-white">
                <h2 class="text-lg font-black tracking-tight">Profil Saya</h2>
                <p class="text-xs text-indigo-200 font-medium mt-0.5">Kelola informasi akun dan lokasi operasional Anda</p>
            </div>
        </div>

        <form action="{{ route('penyedia.profil.update') }}" method="POST" enctype="multipart/form-data" class="p-8"
              x-data="{ loading: false }" @submit="loading = true">
            @csrf
            @method('PUT')
            <input type="hidden" name="email" value="{{ $user->email }}">

            <div class="flex flex-col md:flex-row gap-8">

                {{-- ── Left: Foto + Status ── --}}
                <div class="flex flex-col items-center md:w-56 flex-shrink-0">
                    <div class="relative group cursor-pointer mb-4">
                        <img src="{{ $user->foto_profil_url }}" alt="Foto Profil"
                             class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-[0_8px_24px_rgba(0,0,0,0.10)]">
                        <div class="absolute inset-0 bg-black/50 rounded-2xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                            <svg class="w-6 h-6 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-white text-[10px] font-black">Ubah Foto</span>
                        </div>
                        <input type="file" name="foto_profil" accept="image/jpeg,image/png,image/jpg"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    <p class="text-[10px] text-gray-400 text-center font-medium leading-relaxed">Klik foto untuk mengubah.<br>Maks 2MB (JPG/PNG).</p>
                    @error('foto_profil') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror

                    {{-- Status Card --}}
                    <div class="mt-5 w-full p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center space-y-4">
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Status Verifikasi</p>
                            @if($user->status_verifikasi == 'diverifikasi')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-black px-3 py-1.5 rounded-xl border border-emerald-200/60">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Terverifikasi
                                </span>
                            @elseif($user->status_verifikasi == 'pending')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-black px-3 py-1.5 rounded-xl border border-amber-200/60">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 text-xs font-black px-3 py-1.5 rounded-xl border border-rose-200/60">
                                    Ditolak
                                </span>
                            @endif
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Rating</p>
                            <div class="flex items-center justify-center gap-1.5 text-amber-500 font-black text-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                {{ number_format($user->rating_rata_rata ?? 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Right: Form Fields ── --}}
                <div class="flex-1 space-y-5">

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap / Nama Bisnis <span class="text-rose-500">*</span></label>
                        <x-input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required placeholder="Nama Anda atau nama bisnis..." :hasError="$errors->has('nama_lengkap')" />
                        @error('nama_lengkap') <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email (disabled) --}}
                    <div>
                        <label for="email" class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Email Login</label>
                        <x-input id="email" type="email" value="{{ $user->email }}" disabled />
                        <p class="text-[11px] text-gray-400 font-medium mt-1.5">Email tidak dapat diubah.</p>
                    </div>

                    {{-- No. Telepon --}}
                    <div>
                        <label for="no_telepon" class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">No. Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                        <x-input id="no_telepon" type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required placeholder="08xxxxxxxxxx" :hasError="$errors->has('no_telepon')" />
                        <p class="text-[11px] text-gray-400 font-medium mt-1.5">Gunakan nomor aktif agar pelanggan mudah menghubungi Anda.</p>
                        @error('no_telepon') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lokasi & Alamat dengan Peta Interaktif --}}
                    <x-location-picker
                        label="Alamat Pangkalan/Basis Operasi"
                        hint="Klik pada peta, geser pin, atau cari lokasi basis operasi Anda. Alamat & koordinat akan terisi otomatis."
                        :alamat="old('alamat', $user->alamat)"
                        :lat="old('latitude', $user->latitude)"
                        :lng="old('longitude', $user->longitude)" />

                    {{-- Submit --}}
                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" :disabled="loading"
                            class="inline-flex items-center gap-2 px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl shadow-sm shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-70">
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Ganti Password ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.04)] overflow-hidden mt-6">
        <div class="h-2 bg-gradient-to-r from-rose-500 to-rose-600"></div>
        <div class="p-8">
            <h3 class="text-base font-black text-gray-900 mb-1">Ganti Password</h3>
            <p class="text-xs text-gray-400 font-semibold mb-6">Gunakan password yang kuat minimal 8 karakter.</p>

            @if(session('status') === 'password-updated')
            <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Password berhasil diperbarui.
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                @method('PUT')
                <div class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required autocomplete="current-password"
                            class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-indigo-400/20 focus:border-indigo-400 transition-all text-sm font-medium outline-none @error('current_password', 'updatePassword') border-red-400 @enderror">
                        @error('current_password', 'updatePassword')
                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Baru</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-indigo-400/20 focus:border-indigo-400 transition-all text-sm font-medium outline-none @error('password', 'updatePassword') border-red-400 @enderror">
                        @error('password', 'updatePassword')
                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-indigo-400/20 focus:border-indigo-400 transition-all text-sm font-medium outline-none">
                    </div>
                    <div class="pt-2">
                        <button type="submit" :disabled="loading"
                            class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-md shadow-rose-500/20 disabled:opacity-70 flex items-center gap-2">
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                            Perbarui Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


