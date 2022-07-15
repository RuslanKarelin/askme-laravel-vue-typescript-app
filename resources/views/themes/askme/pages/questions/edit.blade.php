@extends('layouts.askme')

@section('title')
    Edit
@endsection

@section('after-header-block')
    @include('themes.askme.partials.after-header-block', ['title' => 'Edit'])
@endsection

@section('content')
    Edit {{$question->title}}
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection