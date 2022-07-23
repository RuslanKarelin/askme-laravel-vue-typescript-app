<div class="widget widget_stats">
    <h3 class="widget_title">Stats</h3>
    <div class="ul_list ul_list-icon-ok">
        <ul>
            <li><i class="icon-question-sign"></i>Questions ( <span>{{$question_count}}</span> )</li>
            <li><i class="icon-comment"></i>Answers ( <span>{{$answer_count}}</span> )</li>
        </ul>
    </div>
</div>

@if(!empty($tags))
<div class="widget widget_tag_cloud">
    <h3 class="widget_title">Tags</h3>
    @foreach($tags as $tag)
        <a href="{{route('search', ['tag' => $tag])}}">{{$tag}}</a>
    @endforeach
</div>
@endif

@if($last_questions->isNotEmpty())
<div class="widget">
    <h3 class="widget_title">Recent Questions</h3>
    <ul class="related-posts">
        @foreach($last_questions as $question)
        <li class="related-item">
            <h3><a href="{{route('questions.show', ['question' => $question])}}">{{$question->title}}</a></h3>
            <p>{{Illuminate\Support\Str::words($question->detail, 10)}}</p>
            <div class="clear"></div><span>{{$question->created_at->format('M d, Y')}}</span>
        </li>
        @endforeach
    </ul>
</div>
@endif