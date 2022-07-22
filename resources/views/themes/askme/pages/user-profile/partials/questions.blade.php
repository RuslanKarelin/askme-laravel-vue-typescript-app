@include('themes.askme.pages.user-profile.partials.show', ['user' => $user])
@if($questions->isNotEmpty())
    <div class="col-md-12">
        <div class="page-content page-content-user">
            <div class="user-questions">
                @each('themes.askme.pages.questions.partials.list.item', $questions, 'question')
            </div>
        </div>
        {{ $questions->links('themes.askme.pagination.custom') }}
    </div>
@else
    Nothing was found for your query
@endif