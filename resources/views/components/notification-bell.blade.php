@php
    $unread = auth()->user()->unreadNotifications->take(8);
    $unreadCount = auth()->user()->unreadNotifications->count();
@endphp

<div x-data="{ open: false }" class="relative" @keydown.escape.window="open = false" @click.outside="open = false">
    <button @click="open = !open"
        class="relative p-2.5 rounded-xl text-gray-400 hover:bg-gray-50 hover:text-indigo-600 transition-all focus:outline-none"
        title="Notifikasi">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
        @endif
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
         x-cloak
         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50"
         style="transform-origin: top right;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/60">
            <div class="flex items-center gap-2">
                <p class="text-sm font-extrabold text-gray-900">Notifikasi</p>
                @if($unreadCount > 0)
                    <span class="text-[10px] font-black bg-indigo-500 text-white px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                @endif
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifikasi.baca-semua') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        {{-- Notification list --}}
        <div class="max-h-80 overflow-y-auto">
            @forelse($unread as $notif)
                @php
                    $data = $notif->data;
                    $tipe = $data['tipe'] ?? 'info';
                    $dotColors = ['success' => 'bg-emerald-400', 'error' => 'bg-rose-400', 'warning' => 'bg-amber-400', 'info' => 'bg-indigo-400'];
                    $dot = $dotColors[$tipe] ?? 'bg-indigo-400';
                @endphp
                <a href="{{ route('notifikasi.baca', $notif->id) }}"
                   class="flex items-start gap-3 px-4 py-3.5 hover:bg-indigo-50/50 transition-colors border-b border-gray-50 last:border-0 group">
                    <div class="w-2 h-2 rounded-full {{ $dot }} flex-shrink-0 mt-1.5"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $data['judul'] ?? '' }}</p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5 leading-relaxed line-clamp-2">{{ $data['pesan'] ?? '' }}</p>
                        <p class="text-[10px] text-gray-400 font-semibold mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <div class="px-4 py-10 text-center">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500">Tidak ada notifikasi baru</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
