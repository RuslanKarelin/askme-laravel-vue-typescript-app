@if($list->isNotEmpty())
    <div class="page-content page-content-user">
        <div class="user-questions">
            @each('themes.askme.pages.questions.partials.list.item', $list, 'question')
        </div>
    </div>
    {{ $list->links('themes.askme.pagination.custom') }}
@else
    Nothing was found for your query
@endif
