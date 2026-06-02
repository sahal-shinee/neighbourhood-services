@props(['variant' => 'primary', 'type' => 'submit'])

@php
    $baseClasses = 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500 shadow-md hover:shadow-indigo-200',
        'secondary' => 'bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-50 focus:ring-indigo-500',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white focus:ring-red-500 shadow-md hover:shadow-red-200',
        'ghost' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:ring-gray-500',
        default => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500 shadow-md',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
    {{ $slot }}
</button>
