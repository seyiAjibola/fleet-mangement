@if ($paginator->hasPages())
    <nav class="pagination-shell" role="navigation" aria-label="Pagination Navigation">
        <div class="pagination-panel">
            <div class="pagination-summary">
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
            </div>

            <div class="pagination-nav">
                @if ($paginator->onFirstPage())
                    <span class="pagination-link is-disabled" aria-disabled="true" aria-label="Previous page">Prev</span>
                @else
                    <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">Prev</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="pagination-gap" aria-hidden="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-link" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="pagination-link" href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">Next</a>
                @else
                    <span class="pagination-link is-disabled" aria-disabled="true" aria-label="Next page">Next</span>
                @endif
            </div>
        </div>
    </nav>
@endif
