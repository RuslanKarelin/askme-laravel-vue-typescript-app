@extends('layouts.askme')

@section('after-header-block')
    @include('themes.askme.partials.after-header-block')
@endsection

@section('content')
    @include('themes.askme.pages.questions.partials.forms.edit-form')
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection