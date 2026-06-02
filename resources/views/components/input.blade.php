@props(['disabled' => false, 'hasError' => false])

@php
    $base = 'block w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-2.5 text-sm font-medium text-gray-800 placeholder-gray-400 transition-all duration-200 hover:bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 focus:outline-none';
    $disabled_cls = $disabled ? ' opacity-60 cursor-not-allowed bg-gray-100' : '';
    $error_cls    = $hasError  ? ' border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : '';
    $classes = $base . $disabled_cls . $error_cls;
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
