@extends('layouts.guest')
@section('title', 'Buat Sandi Baru')

@section('content')

<div class="mb-10 animate-fade-in-up">
    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-5">
        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
    </div>
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Buat Sandi Baru</h1>
    <p class="text-gray-500 font-semibold text-sm">Masukkan kata sandi baru untuk akun Anda di bawah ini.</p>
</div>

<form method="POST" action="{{ route('password.store') }}" class="space-y-5" x-data="{ showPass: false, showConfirm: false, password: '', password_confirmation: '' }">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    {{-- Email --}}
    <div class="animate-fade-in-up">
        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
        <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                class="block w-full bg-gray-50/70 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
        </div>
        @error('email') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
    </div>

    {{-- New Password --}}
    <div class="animate-fade-in-up delay-100">
        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi Baru</label>
        <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password"
                placeholder="Minimal 8 karakter" x-model="password"
                class="block w-full bg-gray-55 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-12 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
            <button type="button" @click="showPass = !showPass" x-show="password.length > 0" x-transition class="absolute right-4 text-gray-400 hover:text-gray-600 transition-colors">
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

    {{-- Confirm Password --}}
    <div class="animate-fade-in-up delay-200">
        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
        <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                placeholder="Ulangi kata sandi baru" x-model="password_confirmation"
                class="block w-full bg-gray-55 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-12 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
            <button type="button" @click="showConfirm = !showConfirm" x-show="password_confirmation.length > 0" x-transition class="absolute right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1L23 23"
                          :d="showConfirm
                              ? 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'
                              : 'M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1L23 23'">
                    </path>
                </svg>
            </button>
        </div>
        @error('password_confirmation') <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
    </div>

    <div class="pt-2 animate-fade-in-up delay-300">
        <button type="submit"
            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-brand-500/20 text-lg font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] focus:outline-none transition-all duration-300">
            Simpan Kata Sandi Baru
        </button>
    </div>
</form>

@endsection
