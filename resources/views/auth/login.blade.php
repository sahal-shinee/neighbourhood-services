@extends('layouts.guest')
@section('title', 'Masuk ke Akun')

@section('content')

<!-- Header (Animated Step 1) -->
<div class="mb-10 animate-fade-in-up">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Selamat Datang Kembali</h1>
    <p class="text-gray-500 font-semibold text-sm">Silakan masukkan detail akun Anda untuk melanjutkan ke platform.</p>
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center gap-3 animate-fade-in-up">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5"
      x-data="{ loading: false }" @submit="loading = true">
    @csrf

    <!-- Email Input (Animated Step 2) -->
    <div class="animate-fade-in-up delay-100">
        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
        <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 group-focus-within:scale-110 pointer-events-none select-none transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                placeholder="nama@email.com"
                class="block w-full bg-gray-50/70 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
        </div>
        @error('email') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
    </div>

    <!-- Password Input (Animated Step 3) -->
    <div class="animate-fade-in-up delay-200" x-data="{ showPass: false, password: '' }">
        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
        <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 group-focus-within:scale-110 pointer-events-none select-none transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password" 
                placeholder="••••••••" x-model="password"
                class="block w-full bg-gray-50/70 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-12 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
            {{-- Satu ikon eye yang pathnya berubah berdasarkan state showPass --}}
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
    </div>

    <!-- Remember Me & Forgot Password (Animated Step 4) -->
    <div class="flex items-center justify-between pt-2 animate-fade-in-up delay-300">
        <label for="remember_me" class="inline-flex items-center group cursor-pointer">
            <div class="relative flex items-center justify-center w-5 h-5 mr-3">
                <input id="remember_me" type="checkbox" name="remember" class="peer sr-only">
                <div class="w-5 h-5 border-2 border-gray-300 rounded-lg peer-checked:bg-brand-600 peer-checked:border-brand-600 transition-colors group-hover:scale-105 duration-200"></div>
                <svg class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-semibold text-gray-600 group-hover:text-gray-900 transition-colors">Ingat Saya</span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm font-extrabold text-brand-600 hover:text-brand-800 transition-colors hover:underline">
                Lupa Sandi?
            </a>
        @endif
    </div>

    <!-- Submit Button (Animated Step 5) -->
    <div class="pt-4 animate-fade-in-up delay-400">
        <button type="submit" :disabled="loading"
            class="w-full flex justify-center items-center gap-2 py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-brand-500/20 text-lg font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 hover:shadow-xl hover:shadow-brand-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] focus:outline-none transition-all duration-300 disabled:opacity-80">
            <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
            <span x-text="loading ? 'Memproses...' : 'Login'"></span>
        </button>
    </div>

    <!-- Divider (Animated Step 6) -->
    <div class="relative py-4 animate-fade-in-up delay-500">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-150"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-400 font-bold uppercase tracking-wider text-xs">Belum punya akun?</span>
        </div>
    </div>

    <!-- Register Link (Animated Step 7) -->
    <div class="animate-fade-in-up delay-500">
        <a href="{{ route('register') }}" class="w-full flex justify-center py-3.5 px-4 border-2 border-gray-250 rounded-2xl text-base font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-950 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] focus:outline-none transition-all duration-300">
            Buat Akun Baru
        </a>
    </div>

    <!-- Footer links -->
    <p class="text-center text-xs text-gray-400 font-medium pt-2 animate-fade-in-up delay-500">
        <a href="{{ route('terms') }}" target="_blank" class="hover:text-brand-600 transition-colors">Syarat &amp; Ketentuan</a>
        <span class="mx-2">·</span>
        <a href="{{ route('privacy') }}" target="_blank" class="hover:text-brand-600 transition-colors">Kebijakan Privasi</a>
    </p>
</form>

@endsection
