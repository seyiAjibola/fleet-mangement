@if ($paginator->hasPages())
    <nav class="pagination-shell" role="navigation" aria-label="Pagination Navigation">
        <div class="pagination-panel">
            <div class="pagination-summary">
                @if ($paginator->onFirstPage())
                    Showing first results
                @else
                    More results available
                @endif
            </div>

            <div class="pagination-nav">
                @if ($paginator->onFirstPage())
                    <span class="pagination-link is-disabled" aria-disabled="true" aria-label="Previous page">Prev</span>
                @else
                    <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">Prev</a>
                @endif

                @if ($paginator->hasMorePages())
                    <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">Next</a>
                @else
                    <span class="pagination-link is-disabled" aria-disabled="true" aria-label="Next page">Next</span>
                @endif
            </div>
        </div>
    </nav>
@endif
