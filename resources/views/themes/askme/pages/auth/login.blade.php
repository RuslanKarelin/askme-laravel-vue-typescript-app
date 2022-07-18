@extends('layouts.askme-fullwidth')

@section('title')
    Login
@endsection

@section('after-header-block')
    @include('themes.askme.partials.after-header-block', ['title' => 'Login'])
@endsection

@section('content')
    @include('themes.askme.pages.auth.partials.login')
@endsection

@section('container-class') main-content login-page @endsection