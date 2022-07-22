<div class="page-content ask-question">
    <p class="edit-profile-btn"><a href="{{route('questions.show', ['question' => $question])}}"
                                   class="button color small">Show Question</a></p>
    <div class="boxedtitle page-title"><h2>Ask Question</h2></div>

    <p>Duis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur
        vitae velit in neque dictum blandit. Proin in iaculis neque.</p>
    <div class="form-style form-style-3" id="question-submit">
        <form method="POST" action="{{route('questions.update', ['question' => $question])}}">
            @csrf()
            @method('PUT')
            <div class="form-inputs clearfix">
                <p>
                    <label class="required">Question Title<span>*</span></label>
                    <input type="text" name="title" class="form-input ml" id="question-title2"
                           value="{{old('title', $question->title)}}">
                    <span class="form-description">Please choose an appropriate title for the question to answer it even easier .</span>
                    @error('title')
                    <span class="alert alert-danger ml">{{ $message }}</span>
                    @enderror
                </p>
                <p>
                    <label>Status</label>
                    <span class="styled-select">
                        <select name="status_id">
                            @foreach($statuses as $status)
                            <option @if($status->id === $question->status_id) selected @endif value="{{$status->id}}">{{$status->title}}</option>
                            @endforeach
                        </select>
                    </span>
                </p>
                <p>
                    <label>Tags</label>
                    <input type="text" class="input" name="tags" value="{{old('tags', $tags)}}" id="question_tags"
                           data-seperator=",">
                    <span class="form-description">Please choose  suitable Keywords Ex : <span class="color">question , poll</span> .</span>
                </p>
                <div class="clearfix"></div>
            </div>
            <div id="form-textarea">
                <p>
                    <label class="required">Details<span>*</span></label>
                    <textarea id="question-details" name="detail" aria-required="true" cols="58"
                              rows="8">{{old('detail', $question->detail)}}</textarea>
                    <span class="form-description">Type the description thoroughly and in detail .</span>
                    @error('detail')
                    <span class="alert alert-danger ml">{{ $message }}</span>
                    @enderror
                </p>
            </div>
            <p class="form-submit">
                <input type="submit" id="publish-question" value="Update Your Question"
                       class="button color small submit">
            </p>
        </form>
    </div>
</div>