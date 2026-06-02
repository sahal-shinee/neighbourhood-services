@props(['variant' => 'neutral'])

@php
    $variantClasses = match($variant) {
        'success' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200/60',
        'warning' => 'bg-amber-50  text-amber-700  ring-1 ring-amber-200/60',
        'danger'  => 'bg-rose-50   text-rose-700   ring-1 ring-rose-200/60',
        'info'    => 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200/60',
        'neutral' => 'bg-gray-100  text-gray-600   ring-1 ring-gray-200/60',
        default   => 'bg-gray-100  text-gray-600   ring-1 ring-gray-200/60',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold $variantClasses"]) }}>
    {{ $slot }}
</span>
