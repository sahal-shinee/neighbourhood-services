@props(['jasa', 'isFavorit' => false])

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_5px_20px_rgba(0,0,0,0.015)] hover:shadow-[0_15px_40px_rgba(37,99,235,0.08)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-full group overflow-hidden relative">

    <!-- Thumbnail Image Container -->
    <div class="relative h-48 overflow-hidden flex-shrink-0">
        <img src="{{ $jasa->foto_jasa_url }}" alt="{{ $jasa->nama_jasa }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1621905251189-08b45d6a269e?q=80&w=400&auto=format&fit=crop';">
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

        <!-- Badges on Image -->
        <div class="absolute top-4 left-4">
            <x-badge variant="info" class="bg-white/95 backdrop-blur-sm text-brand-700 shadow-md font-bold px-3 py-1 text-xs">
                {{ $jasa->kategori_jasa }}
            </x-badge>
        </div>

        {{-- Tombol Favorit (hanya untuk pelanggan yang sudah login) --}}
        @auth
        @if(auth()->user()->isPelanggan())
        <div class="absolute top-4 right-4"
             x-data="{ favorit: {{ $isFavorit ? 'true' : 'false' }} }"
             x-init="">
            <button type="button"
                @click.prevent="
                    fetch('{{ route('pelanggan.favorit.toggle', $jasa->id_jasa) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(r => r.json())
                    .then(data => { favorit = data.is_favorit; })
                "
                class="w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-md transition-all hover:scale-110"
                :title="favorit ? 'Hapus dari favorit' : 'Simpan ke favorit'">
                <svg class="w-4 h-4 transition-colors"
                     :class="favorit ? 'text-red-500 fill-current' : 'text-gray-400'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>
        @endif
        @endauth
        
        @if(isset($jasa->distance))
            <div class="absolute top-4 right-4">
                <x-badge variant="neutral" class="bg-white/95 backdrop-blur-sm shadow-md font-extrabold px-2.5 py-1 text-[11px] flex items-center gap-1 text-gray-800">
                    <svg class="w-3.5 h-3.5 text-red-500 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    {{ number_format($jasa->distance, 1, ',', '.') }} km
                </x-badge>
            </div>
        @endif
    </div>

    <!-- Card Body Container -->
    <div class="p-6 flex flex-col flex-grow">
        
        <!-- Service Title -->
        <h3 class="text-base font-extrabold text-gray-900 mb-4 line-clamp-2 leading-snug group-hover:text-brand-600 transition-colors h-12 flex items-start" title="{{ $jasa->nama_jasa }}">
            {{ $jasa->nama_jasa }}
        </h3>
        
        <!-- Provider Info Pill Box -->
        <div class="flex items-center justify-between mb-5 bg-gray-50/50 p-2.5 rounded-2xl border border-gray-100/50">
            <div class="flex items-center space-x-2.5 min-w-0">
                <img src="{{ $jasa->foto_profil ?? 'https://ui-avatars.com/api/?name=' . urlencode($jasa->nama_lengkap ?? 'A') }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-brand-100 flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?name=A'">
                <span class="text-xs font-bold text-gray-600 truncate">{{ $jasa->nama_lengkap ?? $jasa->penyedia->nama_lengkap }}</span>
            </div>
            <div class="flex items-center gap-1 bg-amber-50 text-amber-600 px-2.5 py-1 rounded-xl border border-amber-105 flex-shrink-0 text-[10px] font-black">
                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span>{{ $jasa->rating ?? ($jasa->penyedia->rating_rata_rata ?? 0) }}</span>
            </div>
        </div>

        <!-- Bottom Row (Price and CTA Button) -->
        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
            <div class="min-w-0">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">
                    {{ $jasa->tipe_tarif === 'paket' ? 'Mulai Dari' : 'Tarif' }}
                </span>
                <span class="font-extrabold text-brand-600 text-base leading-none truncate block">
                    {{ $jasa->tarif_label }}
                </span>
            </div>
            <a href="{{ route('pelanggan.penyedia.show', $jasa->id_penyedia) }}"
               class="bg-brand-600 hover:bg-brand-700 text-[11px] font-extrabold text-white px-4 py-2.5 rounded-xl transition-all shadow-[0_4px_12px_rgba(37,99,235,0.15)] hover:scale-[1.03] active:scale-[0.98] whitespace-nowrap flex-shrink-0 inline-flex items-center gap-1">
                Lihat Detail
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        
    </div>
</div>
