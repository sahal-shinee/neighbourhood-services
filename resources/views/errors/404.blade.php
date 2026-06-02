<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan · Neighbourhood Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-6 font-sans">
    <div class="text-center max-w-md">
        <div class="w-28 h-28 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-blue-100">
            <span class="text-5xl font-black text-blue-600">404</span>
        </div>
        <h1 class="text-2xl font-black text-gray-900 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-gray-500 font-medium mb-8 leading-relaxed">Halaman yang Anda cari tidak tersedia, sudah dipindahkan, atau Anda tidak memiliki akses.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl transition-all shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Ke Beranda
            </a>
            <button onclick="history.back()"
               class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-bold px-6 py-3 rounded-2xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </button>
        </div>
    </div>
</body>
</html>
