@extends('layouts.guest')
@section('title', 'Lupa Kata Sandi')

@section('content')

<div class="mb-10 animate-fade-in-up">
    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-brand-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Login
    </a>
    <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center mb-5">
        <svg class="w-7 h-7 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
    </div>
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Lupa Kata Sandi?</h1>
    <p class="text-gray-500 font-semibold text-sm leading-relaxed">
        Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk membuat kata sandi baru.
    </p>
</div>

@if (session('status'))
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-start gap-3 animate-fade-in-up">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <div>
            <p class="font-bold mb-0.5">Tautan Terkirim</p>
            <p>{{ session('status') }}</p>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-5">
    @csrf

    <div class="animate-fade-in-up delay-100">
        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
        <div class="relative flex items-center group">
            <div class="absolute left-4 text-gray-400 group-focus-within:text-brand-600 pointer-events-none transition-colors duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="nama@email.com"
                class="block w-full bg-gray-50/70 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:bg-white focus:border-brand-500 premium-input-field outline-none sm:text-base font-medium">
        </div>
        @error('email')
            <p class="text-sm text-red-500 mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-2 animate-fade-in-up delay-200">
        <button type="submit"
            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-brand-500/20 text-lg font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 hover:shadow-xl hover:shadow-brand-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] focus:outline-none transition-all duration-300">
            Kirim Tautan Reset Sandi
        </button>
    </div>
</form>

@endsection
