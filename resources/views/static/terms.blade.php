@extends('layouts.landing')
@section('title', 'Syarat & Ketentuan - Neighbourhood Services')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 sm:p-12">
        <h1 class="text-2xl font-black text-gray-900 mb-2">Syarat &amp; Ketentuan</h1>
        <p class="text-xs text-gray-400 font-semibold mb-8">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <div class="prose prose-sm max-w-none space-y-6 text-gray-700 font-medium leading-relaxed">

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">1. Penerimaan Ketentuan</h2>
                <p>Dengan menggunakan platform Neighbourhood Services, Anda menyetujui syarat dan ketentuan ini. Jika Anda tidak setuju, harap hentikan penggunaan layanan kami.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">2. Deskripsi Layanan</h2>
                <p>Neighbourhood Services adalah platform marketplace yang menghubungkan pelanggan dengan penyedia jasa di lingkungan sekitar. Kami bertindak sebagai perantara dan tidak bertanggung jawab langsung atas kualitas jasa yang diberikan oleh penyedia.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">3. Pendaftaran Akun</h2>
                <p>Pengguna wajib memberikan informasi yang benar, akurat, dan lengkap saat mendaftar. Penyedia jasa wajib mengunggah foto KTP yang valid untuk proses verifikasi identitas oleh admin.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">4. Kewajiban Pengguna</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Tidak menggunakan platform untuk tujuan ilegal atau melanggar hukum.</li>
                    <li>Tidak menyebarkan informasi palsu atau menyesatkan.</li>
                    <li>Menghormati pengguna lain dan menjaga etika dalam berkomunikasi.</li>
                    <li>Tidak menyalahgunakan sistem laporan untuk memfitnah penyedia jasa.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">5. Verifikasi Penyedia</h2>
                <p>Setiap penyedia jasa harus melalui proses verifikasi identitas oleh admin sebelum dapat menawarkan layanan. Admin berhak menolak atau mencabut status verifikasi apabila ditemukan pelanggaran.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">6. Pemesanan dan Pembatalan</h2>
                <p>Pelanggan dapat membatalkan pesanan selama masih berstatus "menunggu". Penyedia dapat menolak atau menyetujui pesanan. Perselisihan antara pelanggan dan penyedia diselesaikan secara langsung antar pihak.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">7. Penangguhan Akun</h2>
                <p>Admin berhak menangguhkan atau menonaktifkan akun pengguna yang terbukti melanggar ketentuan ini, termasuk atas dasar laporan yang valid dari pengguna lain.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">8. Perubahan Ketentuan</h2>
                <p>Kami berhak mengubah ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui platform dan berlaku sejak tanggal pembaruan.</p>
            </section>

        </div>

        <div class="mt-10 pt-6 border-t border-gray-100">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
