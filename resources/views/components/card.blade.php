<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl shadow-[0_2px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-400 border border-gray-100/60 p-6 sm:p-8 relative overflow-hidden group']) }}>
    <!-- Subtle gradient overlay on hover -->
    <div class="absolute inset-0 bg-gradient-to-br from-brand-50/0 to-brand-50/0 group-hover:from-brand-50/50 transition-colors duration-400 pointer-events-none"></div>
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
