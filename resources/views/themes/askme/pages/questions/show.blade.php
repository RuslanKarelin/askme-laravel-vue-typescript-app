@extends('layouts.askme')

@section('after-header-block')
    @include('themes.askme.partials.after-header-block')
@endsection

@section('content')
    @include('themes.askme.pages.questions.partials.show')
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection

@push('scripts')
    <script src="{{asset('js/app/components/answers-list.js')}}"></script>
    <script src="{{asset('js/app/components/like.js')}}"></script>
@endpush