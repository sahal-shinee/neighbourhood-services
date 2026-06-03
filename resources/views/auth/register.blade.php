@extends('layouts.guest')
@section('title', 'Buat Akun Baru')

@section('content')

<!-- Header (Animated Step 1) -->
<div class="mb-8 animate-fade-in-up">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Mulai Perjalanan Anda</h1>
    <p class="text-gray-500 font-semibold text-sm">Bergabunglah sebagai Pelanggan atau Penyedia Jasa hari ini.</p>
</div>

<div x-data="{ 
    tab: '{{ old('peran', 'pelanggan') }}',
    password: '',
    showPass: false,
    get strength() {
        if (!this.password) return { score: 0, text: 'Kosong', color: 'bg-gray-200', textColor: 'text-gray-400' };
        let score = 0;
        if (this.password.length >= 8) score++;
        if (/[A-Z]/.test(this.password)) score++;
        if (/[0-9]/.test(this.password)) score++;
        if (/[^A-Za-z0-9]/.test(this.password)) score++;
        
        if (score <= 1) return { score: 1, text: 'Sangat Lemah', color: 'bg-red-500', textColor: 'text-red-500' };
        if (score === 2) return { score: 2, text: 'Sedang', color: 'bg-orange-500', textColor: 'text-orange-500' };
        if (score === 3) return { score: 3, text: 'Kuat', color: 'bg-green-500', textColor: 'text-green-500' };
        return { score: 4, text: 'Sangat Kuat', color: 'bg-emerald-500', textColor: 'text-emerald-500' };
    }
}" class="space-y-6">
    
    <!-- Tab Selector with SVG Icons and Premium dynamic sliding gradient pill (Animated Step 2) -->
    <div class="flex p-1.5 bg-gray-100 rounded-2xl relative border border-gray-200/50 animate-fade-in-up delay-100">
        <!-- Sliding Brand Pill -->
        <div class="absolute inset-y-1.5 w-[calc(50%-0.375rem)] bg-gradient-to-r from-brand-600 to-indigo-600 rounded-xl shadow-[0_4px_15px_rgba(37,99,235,0.25)] transition-all duration-300 ease-in-out z-0"
             :class="tab === 'pelanggan' ? 'translate-x-0' : 'translate-x-full'"></div>
        
        <button @click="tab = 'pelanggan'" type="button" 
            class="flex-1 py-3 text-sm font-bold z-10 transition-all rounded-xl flex items-center justify-center gap-2 outline-none"
            :class="tab === 'pelanggan' ? 'text-white' : 'text-gray-500 hover:text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Saya Pelanggan
        </button>
        <button @click="tab = 'penyedia'" type="button" 
            class="flex-1 py-3 text-sm font-bold z-10 transition-all rounded-xl flex items-center justify-center gap-2 outline-none"
            :class="tab === 'penyedia' ? 'text-white' : 'text-gray-500 hover:text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Saya Penyedia Jasa
        </button>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <input type="hidden" name="peran" :value="tab">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Nama with Inline SVG Icon (Animated Step 3) -->
            <div class="md:col-span-2 animate-fade-in-up delay-150">
                <label for="nama_lengkap" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <div class="relative flex items-center group">
                    <span class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 group-focus-within:scale-110 pointer-events-none select-none transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required 
                        placeholder="Nama Lengkap Anda"
                        class="block w-full bg-gray-55 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
                </div>
                @error('nama_lengkap') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Email with Inline SVG Icon (Animated Step 4) -->
            <div class="md:col-span-2 animate-fade-in-up delay-200">
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                <div class="relative flex items-center group">
                    <span class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 group-focus-within:scale-110 pointer-events-none select-none transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                        placeholder="nama@email.com"
                        class="block w-full bg-gray-55 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
                </div>
                @error('email') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Password & Confirmation (Animated Step 5) -->
            <div class="md:col-span-2 animate-fade-in-up delay-250">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                        <div class="relative flex items-center group">
                            <span class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 group-focus-within:scale-110 pointer-events-none select-none transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required x-model="password"
                                placeholder="••••••••"
                                class="block w-full bg-gray-55 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-12 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
                            <button type="button" @click="showPass = !showPass" x-show="password.length > 0" x-transition
                                    class="absolute right-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors"
                                    :aria-label="showPass ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1L23 23"
                                          :d="showPass
                                              ? 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'
                                              : 'M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1L23 23'">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                        
                        <!-- Interactive Password Strength Meter -->
                        <div class="mt-3 space-y-1.5" x-show="password.length > 0" x-transition>
                            <div class="flex justify-between items-center text-xs font-bold">
                                <span class="text-gray-500">Kekuatan Sandi:</span>
                                <span :class="strength.textColor" x-text="strength.text">Kosong</span>
                            </div>
                            <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-300 rounded-full" :class="strength.color" :style="`width: ${strength.score * 25}%`"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Ulangi Kata Sandi</label>
                        <div class="relative flex items-center group">
                            <span class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 group-focus-within:scale-110 pointer-events-none select-none transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password_confirmation" :type="showPass ? 'text' : 'password'" name="password_confirmation" required 
                                placeholder="••••••••"
                                class="block w-full bg-gray-55 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Telepon with Inline SVG Icon (Animated Step 6) -->
        <div class="animate-fade-in-up delay-300">
            <label for="no_telepon" class="block text-sm font-bold text-gray-700 mb-2 mt-2">No. Telepon / WhatsApp</label>
            <div class="relative flex items-center group">
                <span class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 group-focus-within:scale-110 pointer-events-none select-none transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </span>
                <input id="no_telepon" type="text" name="no_telepon" value="{{ old('no_telepon') }}" required 
                    placeholder="0812xxxxxxxx"
                    class="block w-full bg-gray-55 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
            </div>
            @error('no_telepon') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Lokasi & Alamat dengan Peta Interaktif (Animated Step 7) -->
        <div class="animate-fade-in-up delay-350">
            <x-location-picker
                label="Alamat Lengkap"
                hint="Klik pada peta, geser pin, atau cari alamat. Alamat & koordinat akan terisi otomatis."
                :alamat="old('alamat')"
                :lat="old('latitude')"
                :lng="old('longitude')" />
        </div>

        <!-- Extra for Penyedia (KTP upload container) (Animated x-show) -->
        <div x-show="tab === 'penyedia'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-6" style="display: none;">
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 shadow-sm shadow-blue-500/5">
                <h4 class="font-extrabold text-blue-900 mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.378 0 2.5-1.122 2.5-2.5S12.378 8.5 11 8.5m5.5 7h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Verifikasi Identitas Penyedia
                </h4>
                <p class="text-xs text-blue-700 font-semibold mb-4">Wajib mengunggah foto KTP Anda yang jelas untuk divalidasi admin.</p>
                
                <input type="file" id="foto_ktp" name="foto_ktp" accept="image/jpeg,image/png" class="block w-full text-sm text-blue-800 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-colors bg-white rounded-xl border border-blue-200 cursor-pointer outline-none">
                @error('foto_ktp') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Submit Button (Animated Step 8) -->
        <div class="pt-4 animate-fade-in-up delay-400">
            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-brand-500/20 text-lg font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 hover:shadow-xl hover:shadow-brand-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] focus:outline-none transition-all duration-300">
                Buat Akun Sekarang
            </button>
        </div>

        <p class="text-center text-xs text-gray-400 font-medium mt-4 animate-fade-in-up delay-500 leading-relaxed">
            Dengan mendaftar, Anda menyetujui
            <a href="{{ route('terms') }}" target="_blank" class="font-bold text-brand-600 hover:underline">Syarat &amp; Ketentuan</a>
            dan
            <a href="{{ route('privacy') }}" target="_blank" class="font-bold text-brand-600 hover:underline">Kebijakan Privasi</a>
            kami.
        </p>

        <p class="text-center text-sm text-gray-500 font-medium mt-3 animate-fade-in-up delay-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-extrabold text-brand-600 hover:text-brand-800 transition-colors hover:underline">Masuk di sini</a>
        </p>
    </form>
</div>

@endsection
