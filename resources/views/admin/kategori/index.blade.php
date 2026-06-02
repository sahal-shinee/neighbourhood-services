@extends('layouts.app')
@section('title', 'Manajemen Kategori')
@section('header', 'Kategori Layanan')

@section('content')

<div class="flex flex-col md:flex-row gap-8">

    {{-- List --}}
    <div class="flex-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Daftar Kategori</h3>
                <span class="text-xs font-bold text-gray-400">{{ $kategori->total() }} kategori</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100">
                            <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest w-20">Ikon</th>
                            <th class="px-6 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Kategori</th>
                            <th class="px-6 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($kategori as $i => $k)
                            <tr class="group hover:bg-indigo-50/30 transition-colors duration-150"
                                style="animation: fadeInUp 0.4s {{ $i * 0.05 }}s cubic-bezier(0.16,1,0.3,1) both;">
                                <td class="px-6 py-4 text-center">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-2xl mx-auto border border-indigo-100/50 group-hover:scale-110 transition-transform duration-200">
                                        {{ $k->ikon_kategori }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $k->nama_kategori }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            x-data=""
                                            x-on:click.prevent="
                                                $dispatch('open-modal', 'edit-kategori');
                                                document.getElementById('edit_id').value    = '{{ $k->id_kategori }}';
                                                document.getElementById('edit_nama').value  = '{{ addslashes($k->nama_kategori) }}';
                                                document.getElementById('edit_ikon').value  = '{{ addslashes($k->ikon_kategori) }}';
                                                document.getElementById('edit_form').action = '/admin/kategori/' + '{{ $k->id_kategori }}';
                                            "
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl border border-indigo-100 transition-all hover:scale-105 active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>

                                        <div x-data="{ confirm: false }" class="flex items-center">
                                            <button x-show="!confirm" @click="confirm = true"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl border border-rose-100 transition-all hover:scale-105 active:scale-95">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                            <div x-show="confirm" x-transition class="flex items-center gap-1.5">
                                                <form action="{{ route('admin.kategori.destroy', $k->id_kategori) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-all">Ya</button>
                                                </form>
                                                <button @click="confirm = false" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-all">Tidak</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 border border-gray-100 text-2xl">🗂️</div>
                                        <p class="text-sm font-bold text-gray-700">Belum ada kategori</p>
                                        <p class="text-xs text-gray-400 font-medium mt-1">Tambahkan kategori pertama melalui form di samping.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kategori->hasPages())
                <div class="px-6 pb-5">
                    {{ $kategori->links('components.pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Form Tambah --}}
    <div class="w-full md:w-80">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 sticky top-24">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center border border-indigo-100">
                    <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Tambah Kategori</h3>
            </div>
            <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="nama_kategori" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input id="nama_kategori" type="text" name="nama_kategori" required
                        placeholder="cth: Kebersihan, Reparasi..."
                        class="block w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
                    @error('nama_kategori') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ikon_kategori" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Ikon Emoji <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <div class="flex gap-2">
                        <input id="ikon_kategori" type="text" name="ikon_kategori"
                            placeholder="🧹"
                            class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-2xl font-medium text-center focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
                        <div id="ikon-preview" class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-2xl border border-indigo-100 flex-shrink-0 transition-all">
                            <span class="text-gray-300 text-sm">?</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 font-medium mt-1.5">Gunakan emoji dari keyboard Anda (Windows: Win+. / Mac: Ctrl+Cmd+Space).</p>
                    @error('ikon_kategori') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200 active:scale-[0.97]">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<x-modal name="edit-kategori" maxWidth="sm">
    <div class="p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center border border-indigo-100">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h2 class="text-base font-bold text-gray-900">Edit Kategori</h2>
        </div>

        <form id="edit_form" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" id="edit_id" name="id">

            <div>
                <label for="edit_nama" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Kategori</label>
                <input id="edit_nama" type="text" name="nama_kategori" required
                    class="block w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
            </div>
            <div>
                <label for="edit_ikon" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Ikon Emoji</label>
                <input id="edit_ikon" type="text" name="ikon_kategori"
                    class="block w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-2xl font-medium text-center focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/10 outline-none transition-all">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-modal>

@push('scripts')
<script>
    // Live emoji preview
    const ikonInput = document.getElementById('ikon_kategori');
    const preview = document.getElementById('ikon-preview');
    if (ikonInput && preview) {
        ikonInput.addEventListener('input', function() {
            const val = this.value.trim();
            preview.innerHTML = val
                ? `<span class="text-2xl">${val}</span>`
                : `<span class="text-gray-300 text-sm">?</span>`;
        });
    }
</script>
@endpush

@endsection
