@if ($paginator->hasPages())
    <nav>
        <ul class="pagination flex justify-center space-x-2">
            {{-- Link Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="ajax-link px-3 py-1 border rounded bg-white"
                       data-url="{{ $paginator->previousPageUrl() }}" rel="prev">
                        &laquo;
                    </a>
                </li>
            @endif

            {{-- Link Halaman --}}
            @foreach ($elements as $element)
                {{-- Jika elemen adalah string (misalnya titik-titik) --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Jika elemen adalah array link --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active">
                                <span class="px-3 py-1 border rounded-md text-white bg-[#4268F6]">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   class="ajax-link px-3 py-1 border rounded bg-white"
                                   data-url="{{ $url }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Link Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="ajax-link px-3 py-1 border rounded bg-white"
                       data-url="{{ $paginator->nextPageUrl() }}" rel="next">
                        &raquo;
                    </a>
                </li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
