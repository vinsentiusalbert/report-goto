@if ($paginator->hasPages())
    <nav class="report-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="report-pagination__meta">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </div>
        <div class="report-pagination__links">
            @if ($paginator->onFirstPage())
                <span class="report-pagination__button is-disabled" aria-disabled="true">‹</span>
            @else
                <a class="report-pagination__button" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="report-pagination__ellipsis">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="report-pagination__button is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="report-pagination__button" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="report-pagination__button" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
            @else
                <span class="report-pagination__button is-disabled" aria-disabled="true">›</span>
            @endif
        </div>
    </nav>
@endif
