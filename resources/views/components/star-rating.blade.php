@props(['rating' => 0, 'readonly' => true, 'name' => 'rating'])

@php
    $readonly = filter_var($readonly, FILTER_VALIDATE_BOOLEAN);
@endphp

<div class="flex items-center {{ $readonly ? '' : 'star-rating-input' }}" x-data="{ currentRating: {{ $rating }}, hoverRating: 0 }">
    @if(!$readonly)
        <input type="hidden" name="{{ $name }}" :value="currentRating">
    @endif
    
    @for($i = 1; $i <= 5; $i++)
        <svg 
            class="w-5 h-5 {{ !$readonly ? 'cursor-pointer transition-transform hover:scale-110' : '' }}" 
            :class="{ 
                'text-amber-400': hoverRating >= {{ $i }} || (hoverRating == 0 && currentRating >= {{ $i }}), 
                'text-gray-300': hoverRating < {{ $i }} && (hoverRating != 0 || currentRating < {{ $i }})
            }"
            @if(!$readonly)
                @mouseenter="hoverRating = {{ $i }}"
                @mouseleave="hoverRating = 0"
                @click="currentRating = {{ $i }}"
            @endif
            fill="currentColor" viewBox="0 0 20 20"
        >
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
    @endfor
</div>
