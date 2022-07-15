@extends('layouts.askme')

@section('title')
    {{$question->title}}
@endsection

@section('after-header-block')
    @include('themes.askme.partials.after-header-block', ['title' => 'Show'])
@endsection

@section('content')
    Single {{$question->title}}
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection