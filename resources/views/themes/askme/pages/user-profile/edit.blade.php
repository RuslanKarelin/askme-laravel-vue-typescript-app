@extends('layouts.askme')

@section('title')
    Edit Profile
@endsection

@section('after-header-block')
    @include('themes.askme.partials.after-header-block', ['title' => 'Edit Profile'])
@endsection

@section('content')
    @include('themes.askme.pages.user-profile.partials.forms.edit-form')
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection