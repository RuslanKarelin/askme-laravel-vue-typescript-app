@extends('layouts.askme')

@section('after-header-block')
    @include('themes.askme.pages.home.partials.after-header-block')
@endsection

@section('content')
    @include('themes.askme.pages.home.partials.content')
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection

@push('scripts')
    <script src="{{asset('js/app/components/questions-on-main.js')}}"></script>
@endpush