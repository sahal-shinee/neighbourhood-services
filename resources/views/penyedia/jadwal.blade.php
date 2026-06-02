@extends('layouts.app')
@section('title', 'Kalender Jadwal Saya')
@section('header', 'Kalender Jadwal')
@section('subheader', 'Pesanan yang disetujui ditampilkan sebagai event. Klik event untuk melihat detail.')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    :root {
        --fc-border-color: #f1f5f9;
        --fc-button-bg-color: #ffffff;
        --fc-button-border-color: #e2e8f0;
        --fc-button-text-color: #475569;
        --fc-button-hover-bg-color: #f8fafc;
        --fc-button-hover-border-color: #cbd5e1;
        --fc-button-active-bg-color: #4f46e5;
        --fc-button-active-border-color: #4f46e5;
        --fc-button-active-text-color: #ffffff;
        --fc-today-bg-color: #eef2ff;
        --fc-event-bg-color: #4f46e5;
        --fc-event-border-color: #4338ca;
        --fc-event-text-color: #ffffff;
        --fc-page-bg-color: #ffffff;
    }
    .fc .fc-button {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.72rem;
        border-radius: 0.75rem;
        padding: 0.4rem 0.9rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.15s;
        letter-spacing: 0.01em;
    }
    .fc .fc-button:focus { box-shadow: 0 0 0 3px rgba(99,102,241,0.18); outline: none; }
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(79,70,229,0.25) !important;
    }
    .fc-toolbar.fc-header-toolbar {
        margin-bottom: 1.25rem !important;
        padding: 0.25rem 0;
    }
    .fc-toolbar-title {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 800 !important;
        font-size: 1.05rem !important;
        color: #1e293b !important;
    }
    .fc-col-header-cell {
        background: #f8fafc;
        border-color: #f1f5f9 !important;
    }
    .fc-col-header-cell-cushion {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.7rem;
        color: #64748b;
        text-decoration: none !important;
        padding: 8px 4px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .fc-daygrid-day-number, .fc-timegrid-slot-label-cushion {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.72rem;
        color: #94a3b8;
        text-decoration: none !important;
    }
    .fc-day-today .fc-daygrid-day-number {
        background: #4f46e5;
        color: #fff;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 3px;
    }
    .fc-event {
        border-radius: 8px !important;
        padding: 2px 5px;
        font-weight: 700;
        font-size: 0.68rem;
        cursor: pointer;
        border: none !important;
        box-shadow: 0 2px 8px rgba(79,70,229,0.2);
    }
    .fc-event:hover { opacity: 0.92; transform: scale(1.02); transition: all 0.15s; }
    .fc-timegrid-slot { height: 2.5rem; }
    .fc-timegrid-slot-label { vertical-align: middle; }
    .fc-scrollgrid { border-radius: 1rem; overflow: hidden; }
    .fc-scrollgrid, .fc-scrollgrid td, .fc-scrollgrid th { border-color: #f1f5f9 !important; }
    .fc .fc-timegrid-now-indicator-line { border-color: #ef4444; border-width: 2px; }
    .fc .fc-timegrid-now-indicator-arrow { border-color: #ef4444; }
</style>
@endpush

@section('content')

{{-- ── Stats Row ──────────────────────────────────────────────────────────── --}}
@php
    $totalEvent  = $pesanan->count();
    $eventMingguIni = $pesanan->filter(function($p) {
        return $p->tanggal_booking->between(now()->startOfWeek(), now()->endOfWeek());
    })->count();
    $eventHariIni = $pesanan->filter(fn($p) => $p->tanggal_booking->isToday())->count();
    $eventMendatang = $pesanan->filter(fn($p) => $p->tanggal_booking->isFuture())->count();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $statCards = [
            ['label' => 'Total Jadwal', 'value' => $totalEvent, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'indigo'],
            ['label' => 'Minggu Ini', 'value' => $eventMingguIni, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'blue'],
            ['label' => 'Hari Ini', 'value' => $eventHariIni, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald'],
            ['label' => 'Akan Datang', 'value' => $eventMendatang, 'icon' => 'M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
        ];
        $colorMap = [
            'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-500', 'border' => 'border-indigo-100', 'val' => 'text-indigo-700'],
            'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-500',   'border' => 'border-blue-100',   'val' => 'text-blue-700'],
            'emerald'=> ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-500','border' => 'border-emerald-100','val' => 'text-emerald-700'],
            'amber'  => ['bg' => 'bg-amber-50',   'icon' => 'text-amber-500',  'border' => 'border-amber-100',  'val' => 'text-amber-700'],
        ];
    @endphp
    @foreach($statCards as $s)
    @php $c = $colorMap[$s['color']]; @endphp
    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 {{ $c['bg'] }} border {{ $c['border'] }} rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black {{ $c['val'] }} leading-none">{{ $s['value'] }}</p>
            <p class="text-xs font-semibold text-gray-400 mt-0.5">{{ $s['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Legend + Info ──────────────────────────────────────────────────────── --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-indigo-600 inline-block"></span>
            <span class="text-xs font-bold text-gray-500">Per Jam / Per Pengerjaan</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-violet-600 inline-block"></span>
            <span class="text-xs font-bold text-gray-500">Paket / Proyek</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
            <span class="text-xs font-bold text-gray-500">Sekarang</span>
        </div>
    </div>
    <div class="flex items-center gap-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-xl">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Klik event untuk detail & kontak pelanggan
    </div>
</div>

{{-- ── Calendar ───────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
    <div id="calendar" class="min-h-[600px]"></div>
</div>

{{-- ── Event Detail Modal ──────────────────────────────────────────────────── --}}
<div x-data="calendarModal()" x-cloak>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-4"
         @click.self="open = false">

        <div x-show="open"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 translate-y-6 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="bg-white rounded-3xl shadow-2xl shadow-black/10 w-full max-w-sm overflow-hidden border border-gray-100">

            {{-- Modal Header --}}
            <div class="relative bg-gradient-to-r from-indigo-600 to-violet-600 p-6 overflow-hidden">
                <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-sm"></div>
                <div class="absolute -bottom-4 left-8 w-20 h-20 bg-white/5 rounded-full"></div>
                <div class="relative flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-black text-indigo-200 uppercase tracking-widest block mb-1.5">Pesanan Disetujui</span>
                        <h3 class="text-base font-black text-white leading-snug truncate" x-text="event.title"></h3>
                        <p class="text-xs text-indigo-200 font-semibold mt-0.5" x-text="event.pelanggan"></p>
                    </div>
                    <button @click="open = false"
                        class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-2xl flex items-center justify-center transition-all flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-5 space-y-4">

                {{-- Waktu / Durasi --}}
                <template x-if="!event.isPaket">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <div class="flex items-center justify-between">
                            <div class="text-center">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Mulai</span>
                                <span class="text-2xl font-black text-slate-800" x-text="event.start"></span>
                            </div>
                            <div class="flex-1 px-4 text-center">
                                <div class="relative h-0.5 bg-indigo-200 mx-3 mb-1.5">
                                    <div class="absolute inset-0 bg-indigo-400 rounded-full"></div>
                                    <div class="absolute left-1/2 -translate-x-1/2 -top-1 w-2.5 h-2.5 bg-indigo-600 rounded-full ring-2 ring-white shadow-sm"></div>
                                </div>
                                <span class="text-[10px] font-black text-indigo-500" x-text="event.duration"></span>
                            </div>
                            <div class="text-center">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Selesai</span>
                                <span class="text-2xl font-black text-slate-800" x-text="event.end"></span>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="event.isPaket">
                    <div class="bg-violet-50 rounded-2xl p-4 border border-violet-100 text-center">
                        <span class="block text-[9px] font-black text-violet-400 uppercase tracking-widest mb-1">Paket / Proyek</span>
                        <span class="block text-base font-black text-violet-900" x-text="event.paketNama"></span>
                        <span class="inline-block mt-2 bg-violet-100 text-violet-700 text-[10px] font-black px-3 py-1 rounded-full" x-text="'Estimasi ' + event.duration"></span>
                    </div>
                </template>

                {{-- Alamat --}}
                <div class="flex items-start gap-3 p-3 bg-rose-50 border border-rose-100 rounded-2xl">
                    <div class="w-8 h-8 bg-white border border-rose-200 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black text-rose-400 uppercase tracking-wider mb-0.5">Alamat Pengerjaan</p>
                        <p class="text-sm font-semibold text-rose-900 leading-relaxed" x-text="event.alamat"></p>
                        <a :href="'https://maps.google.com/?q=' + encodeURIComponent(event.alamat)" target="_blank"
                           class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 hover:text-rose-800 mt-1 transition-colors">
                            Buka Google Maps
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Telepon + WhatsApp --}}
                <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-100 rounded-2xl">
                    <div class="w-8 h-8 bg-white border border-emerald-200 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider mb-0.5">Telepon Pelanggan</p>
                        <p class="text-sm font-bold text-emerald-900" x-text="event.telepon"></p>
                    </div>
                    <a :href="'https://wa.me/' + event.wa" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#25D366] hover:bg-[#128C7E] text-white text-xs font-black rounded-xl transition-all shadow-sm shadow-green-500/20 flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.418-.1.824z"/></svg>
                        WA
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
<script>
    function calendarModal() {
        return {
            open: false,
            event: { title: '', pelanggan: '', isPaket: false, paketNama: '', start: '', end: '', duration: '', alamat: '', telepon: '', wa: '' },
            show(data) { this.event = data; this.open = true; }
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        const events = [
            @foreach($pesanan as $p)
            @php
                $isPaket    = $p->jasa->tipe_tarif === 'paket';
                $startDate  = $p->tanggal_booking->format('Y-m-d');
                if ($isPaket) {
                    $eventStart = $startDate;
                    $eventEnd   = $p->tanggal_booking->copy()->addDays($p->estimasi_hari ?? 1)->format('Y-m-d');
                } else {
                    $eventStart = $startDate . 'T' . \Carbon\Carbon::parse($p->jam_mulai)->format('H:i:s');
                    $eventEnd   = $startDate . 'T' . \Carbon\Carbon::parse($p->jam_selesai)->format('H:i:s');
                }
                $bgColor     = $isPaket ? '#7c3aed' : '#4f46e5';
                $durationLabel = $isPaket
                    ? ($p->estimasi_hari ?? 1) . ' Hari'
                    : \Carbon\Carbon::parse($p->jam_selesai)->diffInHours(\Carbon\Carbon::parse($p->jam_mulai)) . ' Jam';
                $paketNama = $isPaket && $p->paket ? $p->paket->nama_paket : '';
                $waNumber  = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p->pelanggan->no_telepon));
            @endphp
            {
                title:           '{{ addslashes($p->jasa->nama_jasa) }}',
                start:           '{{ $eventStart }}',
                end:             '{{ $eventEnd }}',
                allDay:          {{ $isPaket ? 'true' : 'false' }},
                backgroundColor: '{{ $bgColor }}',
                borderColor:     'transparent',
                extendedProps: {
                    pelanggan:  '{{ addslashes($p->pelanggan->nama_lengkap) }}',
                    isPaket:    {{ $isPaket ? 'true' : 'false' }},
                    paketNama:  '{{ addslashes($paketNama) }}',
                    startTime:  '{{ $isPaket ? $startDate : \Carbon\Carbon::parse($p->jam_mulai)->format("H:i") }}',
                    endTime:    '{{ $isPaket ? $eventEnd : \Carbon\Carbon::parse($p->jam_selesai)->format("H:i") }}',
                    duration:   '{{ $durationLabel }}',
                    alamat:     '{{ addslashes($p->pelanggan->alamat) }}',
                    telepon:    '{{ addslashes($p->pelanggan->no_telepon) }}',
                    wa:         '{{ $waNumber }}',
                }
            },
            @endforeach
        ];

        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            locale:      'id',
            initialView: 'timeGridWeek',
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today:  'Hari Ini',
                month:  'Bulan',
                week:   'Minggu',
                day:    'Hari',
            },
            slotMinTime:  '06:00:00',
            slotMaxTime:  '22:00:00',
            allDaySlot:   true,
            nowIndicator: true,
            events:       events,
            eventContent: function(arg) {
                const p = arg.event.extendedProps;
                return {
                    html: `<div style="padding:4px 6px;overflow:hidden;height:100%;display:flex;flex-direction:column;gap:2px;border-radius:6px;">
                        <div style="font-size:10px;font-weight:800;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            ${arg.event.title}
                        </div>
                        <div style="font-size:9px;font-weight:600;opacity:0.85;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;width:8px;height:8px;vertical-align:middle;margin-right:2px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            ${p.pelanggan}
                        </div>
                        <div style="font-size:9px;opacity:0.75;font-weight:600;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;width:8px;height:8px;vertical-align:middle;margin-right:2px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            ${p.startTime} – ${p.endTime}
                        </div>
                    </div>`
                };
            },
            eventClick: function(info) {
                const p = info.event.extendedProps;
                const el = document.querySelector('[x-data="calendarModal()"]');
                if (el && el._x_dataStack) {
                    const modal = el._x_dataStack[0];
                    modal.show({
                        title:     info.event.title,
                        pelanggan: p.pelanggan,
                        isPaket:   p.isPaket,
                        paketNama: p.paketNama,
                        start:     p.startTime,
                        end:       p.endTime,
                        duration:  p.duration,
                        alamat:    p.alamat,
                        telepon:   p.telepon,
                        wa:        p.wa,
                    });
                }
            },
            windowResize: function(view) { calendar.updateSize(); },
        });
        calendar.render();
    });
</script>
@endpush
