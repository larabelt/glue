@extends('belt-core::layouts.web.main')

@section('meta-title', $category->meta_title)
@section('meta-description', $category->meta_description)
@section('meta-keywords', $category->meta_keywords)

@section('main')

    <div class="container">
        {!! $compiled !!}
    </div>

@endsection