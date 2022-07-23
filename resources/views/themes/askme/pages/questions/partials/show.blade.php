<article class="question single-question question-type-normal">
    @can('edit', $question)
        <div class="question-inner">
            <p class="edit-profile-btn"><a href="{{route('questions.edit', ['question' => $question])}}"
                                           class="button color small">Edit Question</a></p>
        </div>
    @endcan
    <h2>
        <a href="#">{{$question->title}}</a>
    </h2>
    <div class="question-inner">
        <div class="clearfix"></div>
        <div class="question-desc">
            <p>{{$question->detail}}</p>
        </div>
        <div class="question-details">
            <span class="question-answered @if($question->status->title === \App\Enums\QuestionStatuses::Resolved->value) question-answered-done @endif"><i
                        class="icon-ok"></i>{{$question->status->title}}</span>
        </div>
        <span class="question-date"><i class="icon-time"></i>{{$question->created_at->diffForHumans()}}</span>
        <span class="question-comment"><i
                        class="icon-comment"></i>{{$question->answers_count}} Answer</span>
        <span class="question-view"><i class="icon-user"></i>{{$question->state->views}} views</span>
        <div class="like-component">
            <like-component
                    id="{{$question->id}}"
                    type="question"
                    is-user-auth="{{auth()->check()}}"
                    count="{{$question->likes_count}}"
            />
        </div>
        <div class="clearfix"></div>
    </div>
</article>

<div class="share-tags page-content">
    <div class="question-tags"><i class="icon-tags"></i>
        @foreach($question->tags as $tag)
            <a href="{{route('search', ['tag' => $tag->title])}}">{{$tag->title}}</a>@if (!$loop->last), @endif
        @endforeach
    </div>
    <div class="share-inside-warp">
        <ul>
            <li>
                <a href="#" original-title="Facebook">
                    <span class="icon_i">
                        <span class="icon_square" icon_size="20" span_bg="#3b5997" span_hover="#666">
                            <i i_color="#FFF" class="social_icon-facebook"></i>
                        </span>
                    </span>
                </a>
                <a href="#" target="_blank">Facebook</a>
            </li>
            <li>
                <a href="#" target="_blank">
                    <span class="icon_i">
                        <span class="icon_square" icon_size="20" span_bg="#00baf0" span_hover="#666">
                            <i i_color="#FFF" class="social_icon-twitter"></i>
                        </span>
                    </span>
                </a>
                <a target="_blank" href="#">Twitter</a>
            </li>
            <li>
                <a href="#" target="_blank">
                    <span class="icon_i">
                        <span class="icon_square" icon_size="20" span_bg="#ca2c24" span_hover="#666">
                            <i i_color="#FFF" class="social_icon-gplus"></i>
                        </span>
                    </span>
                </a>
                <a href="#" target="_blank">Google plus</a>
            </li>
            <li>
                <a href="#" target="_blank">
                    <span class="icon_i">
                        <span class="icon_square" icon_size="20" span_bg="#e64281" span_hover="#666">
                            <i i_color="#FFF" class="social_icon-dribbble"></i>
                        </span>
                    </span>
                </a>
                <a href="#" target="_blank">Dribbble</a>
            </li>
            <li>
                <a target="_blank" href="#">
                    <span class="icon_i">
                        <span class="icon_square" icon_size="20" span_bg="#c7151a" span_hover="#666">
                            <i i_color="#FFF" class="icon-pinterest"></i>
                        </span>
                    </span>
                </a>
                <a href="#" target="_blank">Pinterest</a>
            </li>
        </ul>
        <span class="share-inside-f-arrow"></span>
        <span class="share-inside-l-arrow"></span>
    </div>
    <div class="share-inside"><i class="icon-share-alt"></i>Share</div>
    <div class="clearfix"></div>
</div>

<div class="about-author clearfix">
    <div class="author-image">
        <a href="{{route('users.profile.show', ['user' => $question->user->id])}}">
            @if (file_exists(storage_path('app/public/'.config('storage.avatars').$question->user->id.'/'.$question->user->profile->avatar)))
                <img src="{{asset('storage/'.config('storage.avatars').$question->user->id.'/'.$question->user->profile->avatar)}}">
            @endif
        </a>
    </div>
    <div class="author-bio">
        <h4>About {{$question->user->profile->fullName()}}</h4>
        {{$question->user->profile->about}}
    </div>
</div>
<script>
    xxx = 'hello';
</script>
<div class="answers">
    <answers-component is-user-auth="{{auth()->check()}}" question-id="{{$question->id}}"/>
</div>