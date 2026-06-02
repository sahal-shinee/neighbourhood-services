@if ($paginator->hasPages())
<nav class="flex items-center justify-between mt-6" aria-label="Pagination">
    {{-- Mobile: simple prev/next --}}
    <div class="flex sm:hidden gap-3 w-full justify-between">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-50 border border-gray-200 rounded-xl cursor-not-allowed select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-indigo-300 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Sebelumnya
            </a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-indigo-300 transition-all">
                Berikutnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-50 border border-gray-200 rounded-xl cursor-not-allowed select-none">
                Berikutnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </div>

    {{-- Desktop: full pagination --}}
    <div class="hidden sm:flex items-center gap-1.5">
        {{-- Prev button --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 bg-white border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-gray-400 select-none">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-sm font-black text-white bg-indigo-600 shadow-sm shadow-indigo-200 select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next button --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 bg-white border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </div>

    <p class="hidden sm:block text-xs font-semibold text-gray-400">
        Menampilkan <span class="font-black text-gray-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span> dari <span class="font-black text-gray-700">{{ $paginator->total() }}</span> data
    </p>
</nav>
@endif
