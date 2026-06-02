@props(['title', 'value', 'color' => 'indigo'])

@php
    $palettes = [
        'indigo'  => ['bg' => 'bg-indigo-500/10',  'ring' => 'ring-indigo-500/20',  'text' => 'text-indigo-600',  'glow' => 'rgba(99,102,241,0.12)',  'bar' => 'bg-indigo-500'],
        'emerald' => ['bg' => 'bg-emerald-500/10', 'ring' => 'ring-emerald-500/20', 'text' => 'text-emerald-600', 'glow' => 'rgba(16,185,129,0.12)',   'bar' => 'bg-emerald-500'],
        'amber'   => ['bg' => 'bg-amber-500/10',   'ring' => 'ring-amber-500/20',   'text' => 'text-amber-600',   'glow' => 'rgba(245,158,11,0.12)',   'bar' => 'bg-amber-500'],
        'rose'    => ['bg' => 'bg-rose-500/10',    'ring' => 'ring-rose-500/20',    'text' => 'text-rose-600',    'glow' => 'rgba(244,63,94,0.12)',    'bar' => 'bg-rose-500'],
        'blue'    => ['bg' => 'bg-blue-500/10',    'ring' => 'ring-blue-500/20',    'text' => 'text-blue-600',    'glow' => 'rgba(59,130,246,0.12)',   'bar' => 'bg-blue-500'],
        'violet'  => ['bg' => 'bg-violet-500/10',  'ring' => 'ring-violet-500/20',  'text' => 'text-violet-600',  'glow' => 'rgba(139,92,246,0.12)',   'bar' => 'bg-violet-500'],
    ];
    $p = $palettes[$color] ?? $palettes['indigo'];
@endphp

<div
    x-data="{
        displayed: 0,
        target: {{ is_numeric($value) ? (int)$value : 0 }},
        isNumeric: {{ is_numeric($value) ? 'true' : 'false' }},
        animated: false,
        animate() {
            if (this.animated || !this.isNumeric) return;
            this.animated = true;
            const duration = 1200;
            const start = performance.now();
            const update = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                this.displayed = Math.floor(ease * this.target);
                if (progress < 1) requestAnimationFrame(update);
                else this.displayed = this.target;
            };
            requestAnimationFrame(update);
        }
    }"
    x-intersect.once="animate()"
    class="relative bg-white rounded-2xl border border-gray-100 p-6 overflow-hidden group cursor-default
           shadow-[0_2px_12px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]
           transition-all duration-300 hover:-translate-y-0.5"
    style="--glow-color: {{ $p['glow'] }};"
>
    {{-- Radial glow on hover --}}
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none rounded-2xl"
         style="background: radial-gradient(circle at top right, var(--glow-color) 0%, transparent 65%);"></div>

    <div class="relative flex items-start justify-between gap-4">
        <div class="flex-1 min-w-0">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">{{ $title }}</p>
            <p class="text-3xl font-black text-gray-900 tracking-tight leading-none">
                <span x-text="isNumeric ? displayed.toLocaleString('id-ID') : '{{ $value }}'">{{ $value }}</span>
            </p>
        </div>

        {{-- Icon Container --}}
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 {{ $p['bg'] }} {{ $p['ring'] }} {{ $p['text'] }}
                    group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
            {{ $slot }}
        </div>
    </div>

    {{-- Bottom accent bar --}}
    <div class="mt-5 pt-4 border-t border-gray-50 flex items-center gap-2">
        <div class="h-1 flex-1 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full {{ $p['bar'] }} rounded-full origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-700 ease-out" style="width: 72%;"></div>
        </div>
        <span class="text-[10px] font-bold text-gray-400 flex-shrink-0">Live</span>
    </div>
</div>
