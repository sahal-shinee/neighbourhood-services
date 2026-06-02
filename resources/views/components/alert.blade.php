@props(['type' => 'info'])

@php
    $typeClasses = match($type) {
        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-500',
        'error', 'danger' => 'bg-red-50 text-red-800 border-red-500',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-500',
        'info' => 'bg-indigo-50 text-indigo-800 border-indigo-500',
        default => 'bg-blue-50 text-blue-800 border-blue-500',
    };

    $iconColor = match($type) {
        'success' => 'text-emerald-500',
        'error', 'danger' => 'text-red-500',
        'warning' => 'text-amber-500',
        'info' => 'text-indigo-500',
        default => 'text-blue-500',
    };
@endphp

<div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 4000)"
    {{ $attributes->merge(['class' => "rounded-xl border-l-4 p-4 shadow-sm mb-4 $typeClasses"]) }} role="alert">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            @if($type == 'success')
                <svg class="h-5 w-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            @elseif($type == 'error' || $type == 'danger')
                <svg class="h-5 w-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            @else
                <svg class="h-5 w-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm font-medium">{{ $slot }}</p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" @click="show = false" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $iconColor }} hover:bg-white/20">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
