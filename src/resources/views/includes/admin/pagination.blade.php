@if ($paginator->hasPages())
    <div class="d-flex justify-content-end">
        <div class="pagination-wrap hstack gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <a class="page-item pagination-prev disabled" href="#">
                    Previous
                </a>
            @else
                <a class="page-item pagination-prev" href="{{ $paginator->previousPageUrl() }}">
                    Previous
                </a>
            @endif
            <ul class="pagination listjs-pagination mb-0">

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><a href="#" class="page">{{ $element }}</a></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active" aria-current="page"><span class="page">{{ $page }}</span></li>
                            @else
                                <li class=""><a class="page" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

            </ul>
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="page-item pagination-next" href="{{ $paginator->nextPageUrl() }}">
                    Next
                </a>
            @else
                <a class="page-item pagination-next disabled" href="#">
                    Next
                </a>
            @endif
        </div>
    </div>
@endif

