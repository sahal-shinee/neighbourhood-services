@extends('layouts.app')
@section('title', 'Profil Pelanggan')
@section('header', 'Pengaturan Profil')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-[2.5rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/80 overflow-hidden">
        
        <!-- Decorative Premium Settings Header -->
        <div class="h-36 bg-gradient-to-r from-brand-600 via-indigo-600 to-brand-700 relative flex items-end px-8 sm:px-12 pb-6">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15)_0%,transparent_60%)] pointer-events-none"></div>
            <div class="absolute -bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full filter blur-xl pointer-events-none"></div>
            
            <div class="relative z-10 text-white">
                <h3 class="text-xl font-bold tracking-tight">Perbarui Akun Anda</h3>
                <p class="text-xs text-brand-100 font-medium mt-1">Kelola data informasi personal Anda untuk kenyamanan bertransaksi.</p>
            </div>
        </div>

        <form action="{{ route('pelanggan.profil.update') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12"
              x-data="{ loading: false }" @submit="loading = true">
            @csrf
            @method('PUT')
            <input type="hidden" name="email" value="{{ $user->email }}">

            <div class="flex flex-col lg:flex-row gap-10">
                
                <!-- Left Column: Interactive Avatar Uploader -->
                <div class="flex flex-col items-center lg:w-1/3 flex-shrink-0">
                    <div class="relative group cursor-pointer w-36 h-36 rounded-full overflow-hidden border-4 border-white shadow-xl hover:shadow-2xl transition-all duration-350 bg-gray-50 flex items-center justify-center">
                        <img src="{{ $user->foto_profil_url }}" alt="Foto Profil" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-350">
                        
                        <!-- Premium Hover Dark Overlay with Camera SVG -->
                        <div class="absolute inset-0 bg-black/55 flex flex-col items-center justify-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-350">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-white text-[10px] font-extrabold uppercase tracking-widest">Ubah Foto</span>
                        </div>
                        
                        <input type="file" name="foto_profil" accept="image/jpeg,image/png,image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" title="Klik untuk mengubah foto">
                    </div>
                    
                    <h4 class="font-extrabold text-gray-900 text-sm mt-4">{{ $user->nama_lengkap }}</h4>
                    <p class="text-[10px] font-black text-brand-600 uppercase tracking-widest mt-1">Pelanggan Terverifikasi</p>
                    
                    <p class="text-[11px] text-gray-400 font-semibold text-center mt-5 leading-relaxed bg-gray-50 p-3 rounded-2xl border border-gray-100/50 w-full">Format gambar JPG, JPEG atau PNG dengan ukuran maksimal 2MB.</p>
                    @error('foto_profil') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Right Column: Premium Form inputs with Crisp SVG Icons -->
                <div class="flex-1 space-y-6">
                    
                    <!-- Nama Lengkap with Inline SVG -->
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required 
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all sm:text-base font-medium outline-none">
                        </div>
                        @error('nama_lengkap') <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email with Inline SVG (ReadOnly) -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Email</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input id="email" type="email" value="{{ $user->email }}" disabled 
                                class="block w-full bg-gray-100 border border-gray-200 text-gray-500 rounded-2xl pl-12 pr-4 py-3.5 sm:text-base font-medium cursor-not-allowed select-none outline-none">
                        </div>
                        <p class="text-[11px] text-gray-400 font-bold mt-1.5 flex items-center gap-1.5 pl-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            Alamat email login bersifat permanen dan tidak dapat diubah.
                        </p>
                    </div>

                    <!-- Telepon with Inline SVG -->
                    <div>
                        <label for="no_telepon" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">No. Telepon / WhatsApp</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <input id="no_telepon" type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required 
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all sm:text-base font-medium outline-none">
                        </div>
                        <p class="text-[11px] text-gray-400 font-semibold mt-1.5 pl-1">Digunakan oleh penyedia jasa untuk berkoordinasi saat akan menuju alamat Anda.</p>
                        @error('no_telepon') <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lokasi & Alamat dengan Peta Interaktif --}}
                    <x-location-picker
                        label="Alamat Rumah Lengkap"
                        hint="Klik pada peta, geser pin, atau cari alamat rumah Anda. Alamat & koordinat akan terisi otomatis."
                        :alamat="old('alamat', $user->alamat)"
                        :lat="old('latitude', $user->latitude)"
                        :lng="old('longitude', $user->longitude)" />

                    <!-- Save Action Button -->
                    <div class="pt-4 flex justify-end">
                        <button type="submit" :disabled="loading"
                            class="bg-brand-600 hover:bg-brand-700 text-white px-8 py-3.5 rounded-2xl font-bold transition-all shadow-[0_8px_20px_rgba(37,99,235,0.25)] hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2">
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    {{-- ── Ganti Password ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden mt-6">
        <div class="h-2 bg-gradient-to-r from-rose-500 to-rose-600"></div>
        <div class="p-8 sm:p-12">
            <h3 class="text-base font-black text-gray-900 mb-1">Ganti Password</h3>
            <p class="text-xs text-gray-400 font-semibold mb-6">Pastikan Anda menggunakan password yang kuat dan tidak mudah ditebak.</p>

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
                            class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3.5 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all text-sm font-medium outline-none @error('current_password', 'updatePassword') border-red-400 @enderror">
                        @error('current_password', 'updatePassword')
                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Baru</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3.5 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all text-sm font-medium outline-none @error('password', 'updatePassword') border-red-400 @enderror">
                        @error('password', 'updatePassword')
                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl px-4 py-3.5 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all text-sm font-medium outline-none">
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
