@if(!empty($pageHelper->breadcrumbs()))
    <div class="crumbs">
        @foreach($pageHelper->breadcrumbs() as $breadcrumb)
            @if(!empty($breadcrumb->url))
                <a href="{{$breadcrumb->url}}">{{$breadcrumb->title}}</a>
            @else
                <span class="current">{{$breadcrumb->title}}</span>
            @endif
            @if (!$loop->last) <span class="crumbs-span">/</span> @endif
        @endforeach
    </div>
@endif