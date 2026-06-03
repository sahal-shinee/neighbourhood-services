@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('header', 'Detail Pesanan #' . str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT))

@section('content')

<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('pelanggan.pesanan.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Pesanan
    </a>

    <div class="flex items-center gap-3">
        @if($pesanan->status_pesanan === 'menunggu')
            <form method="POST" action="{{ route('pelanggan.pesanan.batal', $pesanan->id_pesanan) }}"
                  x-data
                  @submit.prevent="if(confirm('Batalkan pesanan ini?')) $el.submit()">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-4 py-2 rounded-xl transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batalkan Pesanan
                </button>
            </form>
        @endif
        <x-badge :variant="$pesanan->status_variant" class="text-sm px-3 py-1">{{ $pesanan->status_label }}</x-badge>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Jasa & Waktu -->
        <x-card>
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">Informasi Layanan</h3>
            
            <div class="flex items-start gap-4 mb-6">
                <img src="{{ $pesanan->jasa->foto_jasa_url }}" alt="" class="w-24 h-24 rounded-xl object-cover border border-gray-100">
                <div>
                    <x-badge variant="info" class="mb-1">{{ $pesanan->jasa->kategori_jasa }}</x-badge>
                    <h4 class="font-bold text-xl text-gray-900">{{ $pesanan->jasa->nama_jasa }}</h4>
                    <p class="text-indigo-600 font-bold mt-1">{{ $pesanan->jasa->tarif_label }}</p>
                </div>
            </div>

            {{-- Paket info block --}}
            @if($pesanan->jasa->tipe_tarif === 'paket' && $pesanan->paket)
                <div class="mb-4 bg-indigo-50 border border-indigo-100 rounded-2xl p-4">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Paket Dipesan</p>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-black text-base text-indigo-900">{{ $pesanan->paket->nama_paket }}</p>
                            @if($pesanan->paket->deskripsi)
                                <p class="text-xs text-indigo-600 font-medium mt-1 leading-relaxed whitespace-pre-line">{{ $pesanan->paket->deskripsi }}</p>
                            @endif
                        </div>
                        <span class="text-xl font-black text-indigo-700 flex-shrink-0">Rp {{ number_format((float)$pesanan->paket->harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4 bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div>
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tanggal Booking</span>
                    <span class="block font-bold text-gray-900">{{ \Carbon\Carbon::parse($pesanan->tanggal_booking)->translatedFormat('l, d F Y') }}</span>
                </div>

                @if($pesanan->jasa->tipe_tarif === 'paket')
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estimasi Pengerjaan</span>
                        <span class="block font-bold text-gray-900">{{ $pesanan->estimasi_hari }} Hari</span>
                    </div>
                    @if(in_array($pesanan->status_pesanan, ['menunggu', 'disetujui']))
                        <div class="col-span-2 pt-3 border-t border-gray-200 mt-1">
                            <p class="text-xs font-bold text-gray-500 mb-2">Perbarui Estimasi (negosiasi)</p>
                            <form action="{{ route('pelanggan.pesanan.estimasi', $pesanan->id_pesanan) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="estimasi_hari" value="{{ $pesanan->estimasi_hari }}"
                                    min="1" max="365"
                                    class="w-24 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-bold text-gray-900 text-center focus:border-indigo-400 outline-none">
                                <span class="text-sm font-semibold text-gray-500">Hari</span>
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all">
                                    Perbarui
                                </button>
                            </form>
                            @error('estimasi_hari') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    @endif
                @else
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Waktu Pengerjaan</span>
                        <span class="block font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($pesanan->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($pesanan->jam_selesai)->format('H:i') }} WIB
                        </span>
                    </div>
                    @php
                        $durasi = abs(\Carbon\Carbon::parse($pesanan->jam_selesai)->diffInHours(\Carbon\Carbon::parse($pesanan->jam_mulai)));
                        $totalBiaya = $pesanan->jasa->tipe_tarif === 'per_jam'
                            ? $durasi * (float)$pesanan->jasa->tarif_per_jam
                            : (float)$pesanan->jasa->tarif_per_jam;
                        $biayaLabel = $pesanan->jasa->tipe_tarif === 'per_jam'
                            ? "Total Biaya ({$durasi} Jam)"
                            : 'Total Biaya (Per Pengerjaan)';
                    @endphp
                    <div class="col-span-2 pt-3 border-t border-gray-200 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">{{ $biayaLabel }}</span>
                            <span class="text-lg font-black text-indigo-700">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">*Pembayaran dilakukan langsung ke penyedia (tunai) setelah pekerjaan selesai.</p>
                    </div>
                @endif
            </div>
            
            @if($pesanan->catatan_tambahan)
                <div class="mt-6">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan Khusus dari Anda</span>
                    <p class="bg-amber-50 text-amber-900 p-4 rounded-xl border border-amber-100 italic text-sm">"{{ $pesanan->catatan_tambahan }}"</p>
                </div>
            @endif
        </x-card>

        <!-- Bagian Ulasan (Tampil jika sudah selesai) -->
        @if($pesanan->status_pesanan == 'selesai')
            <x-card>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">Ulasan Layanan</h3>
                
                @if($pesanan->ulasan)
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs text-gray-500 font-medium">Ulasan Anda pada {{ \Carbon\Carbon::parse($pesanan->ulasan->tanggal_ulasan)->format('d M Y') }}</span>
                        </div>
                        <div class="mb-3">
                            <x-star-rating :rating="$pesanan->ulasan->rating" readonly="true" />
                        </div>
                        <p class="text-gray-800 italic">"{{ $pesanan->ulasan->komentar_ulasan }}"</p>
                    </div>
                @else
                    <form action="{{ route('pelanggan.ulasan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Berikan Rating Bintang</label>
                            <x-star-rating rating="5" readonly="false" name="rating" />
                            @error('rating') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="komentar_ulasan" class="block text-sm font-bold text-gray-700 mb-2">Tulis Pengalaman Anda</label>
                            <textarea id="komentar_ulasan" name="komentar_ulasan" rows="4" required 
                                class="block w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm font-medium text-gray-800 transition-all duration-200 placeholder-gray-400 hover:bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 focus:outline-none resize-none" 
                                placeholder="Ceritakan bagaimana hasil pekerjaan penyedia jasa ini..."></textarea>
                            @error('komentar_ulasan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end">
                            <x-button type="submit" variant="primary">Kirim Ulasan</x-button>
                        </div>
                    </form>
                @endif
            </x-card>
        @endif
    </div>

    <!-- Informasi Penyedia (Sidebar) -->
    <div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
            <div class="bg-indigo-600 h-20"></div>
            <div class="px-5 pb-5 relative text-center">
                <div class="w-20 h-20 mx-auto -mt-10 mb-3 rounded-full border-4 border-white overflow-hidden bg-white shadow-md">
                    <img src="{{ $pesanan->jasa->penyedia->foto_profil_url }}" alt="" class="w-full h-full object-cover">
                </div>
                
                <h4 class="font-bold text-lg text-gray-900">{{ $pesanan->jasa->penyedia->nama_lengkap }}</h4>
                <p class="text-amber-500 font-bold text-sm mb-4 flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    {{ number_format($pesanan->jasa->penyedia->rating_rata_rata ?? 0, 1) }}
                </p>
                
                <a href="{{ route('pelanggan.penyedia.show', $pesanan->jasa->id_penyedia) }}" class="block w-full py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-sm font-medium transition-colors mb-6 border border-gray-200">
                    Lihat Profil Lengkap
                </a>

                <div class="text-left border-t border-gray-100 pt-4">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kontak Penyedia</span>
                    
                    @if(in_array($pesanan->status_pesanan, ['disetujui', 'selesai']))
                        <div class="flex items-center gap-3 mb-3 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                            <span class="text-indigo-600 bg-white p-1.5 rounded-md shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <span class="font-bold text-indigo-900">{{ $pesanan->jasa->penyedia->no_telepon }}</span>
                        </div>
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $pesanan->jasa->penyedia->no_telepon)) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-[#25D366] hover:bg-[#128C7E] text-white rounded-lg text-sm font-bold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.418-.1.824z"/></svg>
                            Chat WhatsApp
                        </a>
                    @else
                        <div class="text-sm text-gray-500 bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            Kontak akan ditampilkan setelah penyedia jasa <strong>menyetujui</strong> pesanan Anda.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
