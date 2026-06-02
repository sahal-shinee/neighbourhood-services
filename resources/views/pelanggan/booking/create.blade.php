@extends('layouts.app')
@section('title', 'Booking Layanan')
@section('header', 'Booking Layanan')

@push('styles')
<style>
    .flatpickr-day.disabled { background-color:#fee2e2!important;color:#dc2626!important;text-decoration:line-through;border-color:#fee2e2!important; }
    .time-slot { padding:0.375rem 0.625rem;border-radius:0.75rem;font-size:0.75rem;font-weight:700;border:1.5px solid #e5e7eb;color:#374151;background:#f9fafb;cursor:pointer;text-align:center;transition:all .15s; }
    .time-slot:hover { border-color:#6366f1;color:#4f46e5;background:#eef2ff; }
    .time-slot.selected { background:#4f46e5;border-color:#4338ca;color:#fff;box-shadow:0 4px 10px rgba(79,70,229,.25); }
    .time-slot.disabled { opacity:.35;cursor:not-allowed;background:#f3f4f6;text-decoration:line-through; }
    .time-slot.disabled:hover { border-color:#e5e7eb;color:#9ca3af;background:#f3f4f6; }
    .paket-card { border:2px solid #e5e7eb;border-radius:1.25rem;padding:1.25rem;cursor:pointer;transition:all .2s;background:#f9fafb; }
    .paket-card:hover { border-color:#a5b4fc;background:#eef2ff; }
    .paket-card.selected { border-color:#4f46e5;background:#eef2ff;box-shadow:0 0 0 3px rgba(99,102,241,.12); }
</style>
@endpush

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Sidebar --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden sticky top-24">
                <div class="relative h-44 overflow-hidden">
                    <img src="{{ $jasa->foto_jasa_url }}" class="w-full h-full object-cover" alt="">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 left-3">
                        <x-badge variant="info" class="bg-white/90 backdrop-blur-sm text-indigo-700 font-bold">{{ $jasa->kategori_jasa }}</x-badge>
                    </div>
                    @if($jasa->tipe_tarif === 'paket')
                        <div class="absolute top-3 right-3 bg-indigo-600 text-white text-[9px] font-black px-2 py-1 rounded-lg uppercase tracking-wider">Paket</div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="font-black text-base text-gray-900 mb-1 leading-snug">{{ $jasa->nama_jasa }}</h3>

                    @if($jasa->tipe_tarif === 'paket')
                        <p class="text-indigo-600 font-black text-base mb-3">
                            Mulai Rp {{ number_format((float)($jasa->paket->min('harga') ?? 0), 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-indigo-600 font-black text-lg mb-4">
                            Rp {{ number_format((float)$jasa->tarif_per_jam, 0, ',', '.') }}
                            <span class="text-xs text-gray-400 font-normal">{{ $jasa->tarif_suffix }}</span>
                        </p>
                    @endif

                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2.5">Penyedia Jasa</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ $jasa->penyedia->foto_profil_url }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-sm" alt="">
                            <div>
                                <p class="font-bold text-sm text-gray-900">{{ $jasa->penyedia->nama_lengkap }}</p>
                                <div class="flex items-center gap-1 text-amber-500 mt-0.5">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-xs font-black text-amber-600">{{ $jasa->penyedia->rating_rata_rata }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Booking --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.06)] border border-gray-100 p-7">
                <h2 class="text-xl font-black text-gray-900 mb-1">
                    @if($jasa->tipe_tarif === 'paket') Pilih Paket & Jadwal
                    @elseif($jasa->tipe_tarif === 'per_pengerjaan') Tentukan Jadwal Pengerjaan
                    @else Tentukan Jadwal Pengerjaan
                    @endif
                </h2>
                <p class="text-sm text-gray-400 font-medium mb-6">
                    @if($jasa->tipe_tarif === 'paket') Pilih paket yang sesuai dan estimasi waktu penyelesaian.
                    @else Pilih tanggal dan rentang waktu yang Anda butuhkan.
                    @endif
                </p>

                @if($errors->has('overlap'))
                    <div class="mb-5 flex items-start gap-3 bg-rose-50 border border-rose-200 px-4 py-3.5 rounded-2xl">
                        <svg class="w-4 h-4 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-semibold text-rose-800">{{ $errors->first('overlap') }}</p>
                    </div>
                @endif

                <form action="{{ route('pelanggan.booking.store') }}" method="POST" class="space-y-6"
                      x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <input type="hidden" name="jasa_id" value="{{ $jasa->id_jasa }}">
                    <input type="hidden" name="tipe_tarif" value="{{ $jasa->tipe_tarif }}">

                    {{-- ========== PAKET TYPE ========== --}}
                    @if($jasa->tipe_tarif === 'paket')

                        {{-- Pilih Paket --}}
                        <div x-data="{ selectedPaket: '{{ old('id_paket', '') }}', selectedHarga: 0, selectedNama: '' }">
                            <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-3">
                                Pilih Paket <span class="text-rose-500">*</span>
                            </label>
                            <input type="hidden" name="id_paket" :value="selectedPaket">
                            <div class="space-y-3">
                                @foreach($jasa->paket as $paket)
                                    <div @click="selectedPaket = '{{ $paket->id_paket }}'; selectedHarga = {{ (float)$paket->harga }}; selectedNama = '{{ addslashes($paket->nama_paket) }}'"
                                         :class="selectedPaket === '{{ $paket->id_paket }}' ? 'selected' : ''"
                                         class="paket-card">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <div :class="selectedPaket === '{{ $paket->id_paket }}' ? 'bg-indigo-600 border-indigo-600' : 'bg-white border-gray-300'"
                                                         class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all">
                                                        <div x-show="selectedPaket === '{{ $paket->id_paket }}'" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                                    </div>
                                                    <span class="font-black text-sm text-gray-900">{{ $paket->nama_paket }}</span>
                                                    <span class="text-[9px] font-black bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded-md uppercase tracking-wider">
                                                        Tier {{ $loop->iteration }}
                                                    </span>
                                                </div>
                                                @if($paket->deskripsi)
                                                    <p class="text-xs text-gray-500 font-medium ml-6 leading-relaxed whitespace-pre-line">{{ $paket->deskripsi }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <span class="block text-lg font-black text-indigo-700">Rp {{ number_format((float)$paket->harga, 0, ',', '.') }}</span>
                                                <span class="text-[10px] text-gray-400 font-semibold">flat rate</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('id_paket') <p class="mt-1.5 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror

                            {{-- Paket summary card --}}
                            <div x-show="selectedPaket"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="mt-3 bg-indigo-50 border border-indigo-100 rounded-2xl p-4 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] font-black text-indigo-400 uppercase tracking-wider mb-0.5">Paket Dipilih</span>
                                    <span class="block text-sm font-black text-indigo-800" x-text="selectedNama"></span>
                                </div>
                                <span class="text-xl font-black text-indigo-700" x-text="'Rp ' + selectedHarga.toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        {{-- Tanggal mulai --}}
                        <div>
                            <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-2">
                                Tanggal Mulai <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="text" id="tanggal_booking" name="tanggal_booking"
                                       value="{{ old('tanggal_booking') }}" required readonly
                                       placeholder="Pilih tanggal mulai pengerjaan"
                                       class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all cursor-pointer">
                            </div>
                            @error('tanggal_booking') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Estimasi hari --}}
                        <div x-data="{ hari: {{ old('estimasi_hari', 1) }} }">
                            <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-2">
                                Estimasi Durasi Pengerjaan <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-gray-400 font-medium mb-3">Perkiraan jumlah hari yang diperlukan. Dapat dinegosiasikan bersama penyedia setelah booking.</p>
                            <input type="hidden" name="estimasi_hari" :value="hari">
                            <div class="flex items-center gap-4 bg-gray-50 border border-gray-200 rounded-2xl p-4">
                                <button type="button" @click="hari = Math.max(1, hari - 1)"
                                    class="w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center hover:border-indigo-300 hover:bg-indigo-50 transition-all text-gray-600 font-black">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                </button>
                                <div class="flex-1 text-center">
                                    <span class="text-2xl font-black text-indigo-700" x-text="hari"></span>
                                    <span class="text-sm font-bold text-indigo-400 ml-1.5" x-text="hari === 1 ? 'Hari' : 'Hari'"></span>
                                </div>
                                <button type="button" @click="hari = Math.min(365, hari + 1)"
                                    class="w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center hover:border-indigo-300 hover:bg-indigo-50 transition-all text-gray-600 font-black">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                            @error('estimasi_hari') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                    {{-- ========== PER JAM / PER PENGERJAAN ========== --}}
                    @else

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-2">
                                Pilih Tanggal <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="text" id="tanggal_booking" name="tanggal_booking"
                                       value="{{ old('tanggal_booking') }}" required readonly
                                       placeholder="Pilih tanggal pengerjaan"
                                       class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all cursor-pointer">
                            </div>
                            <p id="loading-jadwal" class="text-xs text-gray-400 font-medium mt-1.5">Mengambil jadwal penyedia...</p>
                            @error('tanggal_booking') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Jam --}}
                        <div x-data="timeSelector()" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-2">
                                    Jam Mulai <span class="text-rose-500">*</span>
                                </label>
                                <input type="hidden" name="jam_mulai" :value="startTime">
                                <div class="grid grid-cols-5 sm:grid-cols-7 gap-2">
                                    @for($i=7; $i<=20; $i++)
                                        @php $jam = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                        <button type="button"
                                            @click="selectStart('{{ $jam }}')"
                                            :class="startTime === '{{ $jam }}' ? 'selected' : ''"
                                            class="time-slot text-xs">{{ $jam }}</button>
                                    @endfor
                                </div>
                                @error('jam_mulai') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-2">
                                    Jam Selesai <span class="text-rose-500">*</span>
                                </label>
                                <input type="hidden" name="jam_selesai" :value="endTime">
                                <div class="grid grid-cols-5 sm:grid-cols-7 gap-2">
                                    @for($i=8; $i<=21; $i++)
                                        @php $jam = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                        <button type="button"
                                            @click="selectEnd('{{ $jam }}')"
                                            :class="[endTime === '{{ $jam }}' ? 'selected' : '', isEndDisabled('{{ $jam }}') ? 'disabled' : '']"
                                            :disabled="isEndDisabled('{{ $jam }}')"
                                            class="time-slot text-xs">{{ $jam }}</button>
                                    @endfor
                                </div>
                                @error('jam_selesai') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            {{-- Estimasi Biaya --}}
                            <div x-show="startTime && endTime && duration > 0"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="block text-xs font-black text-indigo-600 uppercase tracking-wider mb-0.5">Estimasi Biaya</span>
                                        @if($jasa->tipe_tarif === 'per_jam')
                                            <span class="block text-sm font-semibold text-indigo-700" x-text="`Durasi: ${duration} Jam × Rp {{ number_format((float)$jasa->tarif_per_jam, 0, ',', '.') }}`"></span>
                                        @else
                                            <span class="block text-sm font-semibold text-indigo-700">Harga tetap (1 pengerjaan)</span>
                                        @endif
                                    </div>
                                    @if($jasa->tipe_tarif === 'per_jam')
                                        <span class="text-2xl font-black text-indigo-800"
                                              x-text="'Rp ' + (duration * {{ (float)$jasa->tarif_per_jam }}).toLocaleString('id-ID')"></span>
                                    @else
                                        <span class="text-2xl font-black text-indigo-800">
                                            Rp {{ number_format((float)$jasa->tarif_per_jam, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-indigo-400 font-semibold mt-2">*Pembayaran dilakukan langsung ke penyedia setelah pekerjaan selesai.</p>
                            </div>
                        </div>

                    @endif

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-2">
                            Catatan Khusus <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="catatan_tambahan" rows="3"
                            class="block w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all resize-none"
                            placeholder="Instruksi khusus, detail masalah, arah lokasi, atau pertanyaan kepada penyedia...">{{ old('catatan_tambahan') }}</textarea>
                    </div>

                    <div class="pt-5 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ url()->previous() }}"
                           class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-all">
                            Batal
                        </a>
                        <button type="submit" :disabled="loading"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200 active:scale-[0.97] disabled:opacity-70">
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="loading ? 'Mengirim...' : 'Kirim Permintaan Booking'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function timeSelector() {
    return {
        startTime: '{{ old('jam_mulai', '') }}',
        endTime:   '{{ old('jam_selesai', '') }}',
        get duration() {
            if (!this.startTime || !this.endTime) return 0;
            return parseInt(this.endTime) - parseInt(this.startTime);
        },
        selectStart(time) {
            this.startTime = time;
            if (this.endTime && parseInt(this.endTime) <= parseInt(time)) this.endTime = '';
        },
        selectEnd(time) { if (!this.isEndDisabled(time)) this.endTime = time; },
        isEndDisabled(time) {
            if (!this.startTime) return false;
            return parseInt(time) <= parseInt(this.startTime);
        }
    };
}

document.addEventListener('DOMContentLoaded', function () {
    @if($jasa->tipe_tarif !== 'paket')
    fetch(`/api/jadwal-terpesan/{{ $jasa->id_penyedia }}`)
        .then(res => res.json())
        .then(data => {
            const booked = data.map(i => i.tanggal_booking);
            flatpickr('#tanggal_booking', { locale:'id', dateFormat:'Y-m-d', minDate:'today', disable: booked, disableMobile:true });
            document.getElementById('loading-jadwal').textContent = 'Tanggal merah sudah penuh.';
        })
        .catch(() => {
            flatpickr('#tanggal_booking', { locale:'id', dateFormat:'Y-m-d', minDate:'today' });
            document.getElementById('loading-jadwal').textContent = '';
        });
    @else
    flatpickr('#tanggal_booking', { locale:'id', dateFormat:'Y-m-d', minDate:'today', disableMobile:true });
    @endif
});
</script>
@endpush
