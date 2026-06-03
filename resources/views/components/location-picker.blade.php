@props([
    'alamat'     => '',
    'lat'        => '',
    'lng'        => '',
    'label'      => 'Alamat Lengkap',
    'hint'       => 'Klik pada peta, geser pin, atau cari alamat. Kolom alamat & koordinat akan terisi otomatis.',
    'defaultLat' => -6.208800,   // Jakarta sebagai titik awal jika belum ada koordinat
    'defaultLng' => 106.845600,
])

{{--
    Komponen Peta Lokasi Interaktif (Leaflet + OpenStreetMap/Esri Satellite).
    GRATIS, tanpa API key. Fitur:
      - Tampilan satelit (default) & peta jalan (toggle kanan atas)
      - Klik peta / geser pin  -> isi alamat otomatis (reverse geocode Nominatim)
      - Ketik di kotak cari      -> daftar lokasi, klik untuk pindah pin (forward geocode)
      - Tombol "Lokasi Saya"     -> deteksi GPS browser
    Output input: name="alamat", name="latitude", name="longitude" (kompatibel controller lama).
    Catatan: pakai ID tetap, jadi gunakan MAKSIMAL satu komponen ini per halaman.
--}}

{{-- Leaflet CSS (inline agar tidak perlu @stack di layout) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="space-y-3">
    {{-- Kotak Pencarian Alamat --}}
    <div class="relative">
        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cari Lokasi di Peta</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
            </span>
            <input id="loc-search" type="text" autocomplete="off"
                placeholder="Ketik nama jalan / tempat, mis. Jl. Asia Afrika Bandung"
                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all text-sm font-medium outline-none">
        </div>
        {{-- Dropdown hasil pencarian --}}
        <div id="loc-results" class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden z-[1100] max-h-64 overflow-y-auto"></div>
    </div>

    {{-- Peta --}}
    <div id="loc-map" class="relative z-0 w-full h-64 sm:h-72 rounded-2xl overflow-hidden border border-gray-200 bg-gray-100"></div>
    <p class="text-[11px] text-gray-400 font-semibold pl-1">{{ $hint }}</p>

    {{-- Tombol GPS + status --}}
    <div class="flex items-center gap-3 flex-wrap">
        <button type="button" id="loc-gps-btn"
            class="inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-bold text-xs px-4 py-2.5 rounded-xl transition-all active:scale-[0.97]">
            <svg id="loc-gps-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span id="loc-gps-text">Gunakan Lokasi Saya (GPS)</span>
        </button>
        <p id="loc-status" class="text-xs font-semibold"></p>
    </div>

    {{-- Alamat Lengkap (terisi otomatis, tetap bisa diedit) --}}
    <div>
        <label for="loc-alamat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">{{ $label }} <span class="text-rose-500">*</span></label>
        <div class="relative flex items-start">
            <span class="absolute left-4 top-3.5 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
            <textarea id="loc-alamat" name="alamat" rows="3" required
                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl pl-12 pr-4 py-3.5 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all text-sm font-medium resize-none outline-none"
                placeholder="Nomor rumah, blok, RT/RW, patokan terdekat...">{{ $alamat }}</textarea>
        </div>
        @error('alamat') <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
    </div>

    {{-- Koordinat (read-only, terisi dari peta) --}}
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1 block">Latitude</label>
            <input id="loc-lat" type="text" name="latitude" value="{{ $lat }}" readonly placeholder="-6.2000000"
                class="block w-full bg-gray-100 border border-gray-200 text-gray-600 rounded-xl px-3 py-2.5 text-sm font-medium outline-none cursor-not-allowed">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1 block">Longitude</label>
            <input id="loc-lng" type="text" name="longitude" value="{{ $lng }}" readonly placeholder="106.8166667"
                class="block w-full bg-gray-100 border border-gray-200 text-gray-600 rounded-xl px-3 py-2.5 text-sm font-medium outline-none cursor-not-allowed">
        </div>
    </div>
    @error('latitude') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
    @error('longitude') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
</div>

{{-- Leaflet JS (sinkron, agar init di bawah langsung jalan) --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    if (typeof L === 'undefined') return;

    var mapEl     = document.getElementById('loc-map');
    var latInput  = document.getElementById('loc-lat');
    var lngInput  = document.getElementById('loc-lng');
    var alamatEl  = document.getElementById('loc-alamat');
    var searchEl  = document.getElementById('loc-search');
    var resultsEl = document.getElementById('loc-results');
    var gpsBtn    = document.getElementById('loc-gps-btn');
    var gpsText   = document.getElementById('loc-gps-text');
    var statusEl  = document.getElementById('loc-status');
    if (!mapEl) return;

    // Perbaiki ikon marker default (sering 404 saat pakai CDN)
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl:       'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl:     'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    var DEFAULT_LAT = {{ $defaultLat }};
    var DEFAULT_LNG = {{ $defaultLng }};
    var hasInitial  = latInput.value !== '' && lngInput.value !== '';
    var startLat    = parseFloat(latInput.value) || DEFAULT_LAT;
    var startLng    = parseFloat(lngInput.value) || DEFAULT_LNG;

    var map = L.map(mapEl).setView([startLat, startLng], hasInitial ? 16 : 5);

    var street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19, attribution: '&copy; OpenStreetMap'
    });
    var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19, attribution: 'Tiles &copy; Esri'
    });
    satellite.addTo(map); // default: satelit
    L.control.layers({ 'Satelit': satellite, 'Peta Jalan': street }, null, { position: 'topright' }).addTo(map);

    var marker = L.marker([startLat, startLng], { draggable: true }).addTo(map);

    function setStatus(msg, type) {
        statusEl.textContent = msg || '';
        statusEl.className = 'text-xs font-semibold ' + (
            type === 'ok' ? 'text-emerald-600' : type === 'err' ? 'text-rose-500' : 'text-gray-500'
        );
    }

    function applyCoords(lat, lng, doReverse) {
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);
        marker.setLatLng([lat, lng]);
        if (doReverse) reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
        setStatus('Mengambil alamat dari peta...', 'info');
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&accept-language=id')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.display_name) {
                    alamatEl.value = data.display_name;
                    setStatus('Alamat berhasil terisi dari peta.', 'ok');
                } else {
                    setStatus('Alamat tidak ditemukan, silakan isi manual.', 'err');
                }
            })
            .catch(function () { setStatus('Gagal mengambil alamat (cek koneksi).', 'err'); });
    }

    // Klik peta & geser pin
    map.on('click', function (e) { applyCoords(e.latlng.lat, e.latlng.lng, true); });
    marker.on('dragend', function () { var p = marker.getLatLng(); applyCoords(p.lat, p.lng, true); });

    // Tombol GPS
    if (gpsBtn) {
        gpsBtn.addEventListener('click', function () {
            if (!navigator.geolocation) { setStatus('Browser tidak mendukung GPS.', 'err'); return; }
            setStatus('Mendeteksi lokasi...', 'info');
            if (gpsText) gpsText.textContent = 'Mendeteksi...';
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    var la = pos.coords.latitude, lo = pos.coords.longitude;
                    map.setView([la, lo], 17);
                    applyCoords(la, lo, true);
                    if (gpsText) gpsText.textContent = 'Gunakan Lokasi Saya (GPS)';
                },
                function () {
                    setStatus('Gagal mendeteksi lokasi. Pastikan izin lokasi diberikan.', 'err');
                    if (gpsText) gpsText.textContent = 'Gunakan Lokasi Saya (GPS)';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    }

    // Pencarian alamat (debounce 700ms)
    var timer = null;
    if (searchEl) {
        searchEl.addEventListener('input', function () {
            clearTimeout(timer);
            var q = searchEl.value.trim();
            if (q.length < 3) { resultsEl.classList.add('hidden'); return; }
            timer = setTimeout(function () { doSearch(q); }, 700);
        });
    }

    function doSearch(q) {
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(q) + '&accept-language=id&countrycodes=id&limit=5')
            .then(function (r) { return r.json(); })
            .then(function (list) {
                resultsEl.innerHTML = '';
                if (!list || !list.length) { resultsEl.classList.add('hidden'); return; }
                list.forEach(function (item) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 border-b border-gray-50 last:border-0 transition-colors';
                    btn.textContent = item.display_name;
                    btn.addEventListener('click', function () {
                        var la = parseFloat(item.lat), lo = parseFloat(item.lon);
                        map.setView([la, lo], 16);
                        applyCoords(la, lo, false);
                        alamatEl.value = item.display_name;
                        setStatus('Lokasi dipilih dari hasil pencarian.', 'ok');
                        resultsEl.classList.add('hidden');
                        searchEl.value = '';
                    });
                    resultsEl.appendChild(btn);
                });
                resultsEl.classList.remove('hidden');
            })
            .catch(function () { resultsEl.classList.add('hidden'); });
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        if (resultsEl && !resultsEl.contains(e.target) && e.target !== searchEl) {
            resultsEl.classList.add('hidden');
        }
    });

    // Pastikan ukuran peta benar setelah layout selesai
    setTimeout(function () { map.invalidateSize(); }, 250);
})();
</script>
