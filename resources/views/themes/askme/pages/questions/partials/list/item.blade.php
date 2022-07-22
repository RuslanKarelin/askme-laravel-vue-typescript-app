<article class="question user-question">
    <h3>
        <a href="{{route('questions.show', ['question' => $question->id])}}">{{$question->title}}</a>
    </h3>
    <div class="question-content">
        <div class="question-bottom">
            <div class="question-answered @if($question->status->title === \App\Enums\QuestionStatuses::Resolved->value) question-answered-done @endif"><i class="icon-ok"></i>{{$question->status->title}}</div>
            <span class="question-date"><i class="icon-time"></i>{{$question->created_at->diffForHumans()}}</span>
            <span class="question-comment"><a href="#"><i class="icon-comment"></i>{{$question->answers_count}} Answers</a></span>
            <span class="question-view"><i class="icon-user"></i>{{$question->state->views}} views</span>
        </div>
    </div>
</article>