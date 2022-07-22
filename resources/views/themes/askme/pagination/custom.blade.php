@if($paginator->hasPages())
        <div class="height_20"></div>
        <div class="pagination">
            @if($paginator->onFirstPage())
                <span class="prev-button"><i class="icon-angle-left"></i></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="prev-button"><i class="icon-angle-left"></i></a>
            @endif

            @foreach($elements as $element)
                @if(is_string($element))
                    <span class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></span>
                @endif

                @if(is_array($element))
                    @foreach($element as $page => $url)
                        @if($page == $paginator->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="next-button"><i class="icon-angle-right"></i></a>
            @else
                <span class="page-link" aria-hidden="true">&rsaquo;</span>
            @endif
        </div>
@endif
