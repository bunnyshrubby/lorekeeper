@extends('layouts.app')

@section('title') {{ $page->title }} @endsection

@section('content')
@if($page->page_category_id && $page->category->section)
{!! breadcrumbs(['World' => 'world', $page->category->section->name => '/world/info/'.$page->category->section->key , $page->title => $page->url])!!}
@else
{!! breadcrumbs([$page->title => $page->url]) !!}
@endif
<x-admin-edit title="Page" :object="$page"/>
<h1>{{ $page->title }}</h1>

<div class="mb-4">
    <div><strong>Created:</strong> {!! format_date($page->created_at) !!}</div>
    <div><strong>Last updated:</strong> {!! format_date($page->updated_at) !!}</div>
</div>

<div class="site-page-content parsed-text">
    {!! $page->parsed_text !!}
</div>

@if($page->can_comment)
    @comments(['model' => $page,
            'perPage' => 5
        ])
@endif

@endsection
