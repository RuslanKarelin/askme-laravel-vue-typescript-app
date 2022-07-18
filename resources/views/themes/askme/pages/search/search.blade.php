@extends('layouts.askme')

@section('title')
    'Search by: '.$searchQuery
@endsection

@section('after-header-block')
    @include('themes.askme.partials.after-header-block', ['title' => 'Search by: '.$searchQuery])
@endsection

@section('content')
    @include('themes.askme.pages.search.partials.index')
@endsection

@section('sidebar')
    @include('themes.askme.partials.right-sidebar')
@endsection