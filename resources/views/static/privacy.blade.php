@extends('layouts.landing')
@section('title', 'Kebijakan Privasi - Neighbourhood Services')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 sm:p-12">
        <h1 class="text-2xl font-black text-gray-900 mb-2">Kebijakan Privasi</h1>
        <p class="text-xs text-gray-400 font-semibold mb-8">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <div class="prose prose-sm max-w-none space-y-6 text-gray-700 font-medium leading-relaxed">

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">1. Data yang Kami Kumpulkan</h2>
                <p>Kami mengumpulkan data berikut saat Anda menggunakan platform:</p>
                <ul class="list-disc pl-5 space-y-1 mt-2">
                    <li>Data identitas: nama lengkap, alamat email, nomor telepon, alamat.</li>
                    <li>Foto KTP (khusus penyedia jasa) untuk keperluan verifikasi identitas.</li>
                    <li>Foto profil yang diunggah secara sukarela.</li>
                    <li>Koordinat GPS jika fitur lokasi diaktifkan (digunakan untuk pencarian terdekat).</li>
                    <li>Riwayat transaksi dan komunikasi dalam platform.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">2. Penggunaan Data</h2>
                <p>Data yang dikumpulkan digunakan untuk:</p>
                <ul class="list-disc pl-5 space-y-1 mt-2">
                    <li>Memverifikasi identitas penyedia jasa.</li>
                    <li>Memfasilitasi transaksi antara pelanggan dan penyedia.</li>
                    <li>Mengirimkan notifikasi terkait status pesanan.</li>
                    <li>Meningkatkan keamanan dan kualitas platform.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">3. Keamanan Data KTP</h2>
                <p>Foto KTP penyedia disimpan dalam penyimpanan privat yang tidak dapat diakses publik secara langsung melalui URL. Akses hanya diberikan kepada admin platform yang terautentikasi untuk keperluan verifikasi.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">4. Data Lokasi</h2>
                <p>Koordinat GPS bersifat opsional dan hanya digunakan untuk mengurutkan hasil pencarian berdasarkan jarak. Data lokasi tidak dibagikan kepada pihak ketiga.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">5. Berbagi Data kepada Pihak Ketiga</h2>
                <p>Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Data hanya dibagikan apabila diwajibkan oleh hukum yang berlaku.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">6. Retensi Data</h2>
                <p>Data akun disimpan selama akun Anda aktif. Laporan yang sudah diselesaikan atau ditolak akan dihapus otomatis setelah 30 hari. Anda dapat meminta penghapusan akun dengan menghubungi admin.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">7. Hak Pengguna</h2>
                <p>Anda berhak mengakses, memperbarui, atau meminta penghapusan data pribadi Anda melalui halaman profil atau dengan menghubungi admin platform.</p>
            </section>

            <section>
                <h2 class="text-base font-black text-gray-900 mb-2">8. Hubungi Kami</h2>
                <p>Jika ada pertanyaan mengenai kebijakan privasi ini, silakan hubungi admin melalui platform.</p>
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
