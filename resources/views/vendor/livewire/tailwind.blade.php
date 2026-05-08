@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav class="pagination-shell" role="navigation" aria-label="Pagination Navigation">
            <div class="pagination-panel">
                <div class="pagination-summary">
                    Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
                </div>

                <div class="pagination-nav">
                    @if ($paginator->onFirstPage())
                        <span class="pagination-link is-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            Prev
                        </span>
                    @else
                        <button
                            type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                            class="pagination-link"
                            aria-label="{{ __('pagination.previous') }}"
                        >
                            Prev
                        </button>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="pagination-gap" aria-hidden="true">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    @if ($page == $paginator->currentPage())
                                        <span class="pagination-link" aria-current="page">{{ $page }}</span>
                                    @else
                                        <button
                                            type="button"
                                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                            class="pagination-link"
                                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                        >
                                            {{ $page }}
                                        </button>
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <button
                            type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                            class="pagination-link"
                            aria-label="{{ __('pagination.next') }}"
                        >
                            Next
                        </button>
                    @else
                        <span class="pagination-link is-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>
