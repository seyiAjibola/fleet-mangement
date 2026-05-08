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
                    @if (method_exists($paginator, 'firstItem') && $paginator->firstItem())
                        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }}
                    @else
                        More results available
                    @endif
                </div>

                <div class="pagination-nav">
                    @if ($paginator->onFirstPage())
                        <span class="pagination-link is-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            Prev
                        </span>
                    @else
                        @if (method_exists($paginator, 'getCursorName'))
                            <button
                                type="button"
                                dusk="previousPage"
                                wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}"
                                wire:click="setPage('{{ $paginator->previousCursor()->encode() }}', '{{ $paginator->getCursorName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                class="pagination-link"
                                aria-label="{{ __('pagination.previous') }}"
                            >
                                Prev
                            </button>
                        @else
                            <button
                                type="button"
                                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                class="pagination-link"
                                aria-label="{{ __('pagination.previous') }}"
                            >
                                Prev
                            </button>
                        @endif
                    @endif

                    @if ($paginator->hasMorePages())
                        @if (method_exists($paginator, 'getCursorName'))
                            <button
                                type="button"
                                dusk="nextPage"
                                wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}"
                                wire:click="setPage('{{ $paginator->nextCursor()->encode() }}', '{{ $paginator->getCursorName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                class="pagination-link"
                                aria-label="{{ __('pagination.next') }}"
                            >
                                Next
                            </button>
                        @else
                            <button
                                type="button"
                                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                class="pagination-link"
                                aria-label="{{ __('pagination.next') }}"
                            >
                                Next
                            </button>
                        @endif
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
