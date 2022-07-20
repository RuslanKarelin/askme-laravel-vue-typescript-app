@extends('layouts.askme')

@section('title')
    Main
@endsection

@section('after-header-block')
    <div class="section-warp ask-me">
        <div class="container clearfix">
            <div class="box_icon box_warp box_no_border box_no_background" box_border="transparent" box_background="transparent" box_color="#FFF">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Welcome to Ask me, Awesome Questions & Answer Template</h2>
                        <p>Duis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae velit in neque dictum blandit.</p>
                        <div class="clearfix"></div>
                        <a class="color button dark_button medium" href="#">About Us</a>
                        <a class="color button dark_button medium" href="#">Join Now</a>
                        <div class="clearfix"></div>
                        <form action="{{route('search')}}" class="form-style form-style-2">
                            <p>
                                <input name="query" type="text" id="question_title" value="Ask any question and you be sure find your answer ?" onfocus="if(this.value=='Ask any question and you be sure find your answer ?')this.value='';" onblur="if(this.value=='')this.value='Ask any question and you be sure find your answer ?';" autocomplete="off">
                                <i class="icon-pencil"></i>
                                <button id="main-search-submit" type="submit" class="color button small publish-question">Ask Now</button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="questions-on-main">
        <question-list-on-main/>
    </div>
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection

@push('scripts')
    <script src="{{asset('js/app/components/questions-on-main.js')}}"></script>
@endpush