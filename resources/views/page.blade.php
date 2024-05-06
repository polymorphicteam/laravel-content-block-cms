@extends('cms::layouts.site')

@section('css')
    <style type="text/css">

    </style>
@stop

@section('cms-content')
    <section class="cms-page">
        <nav aria-label="You are here:" role="navigation" class="nav-breadcrumbs">
            <ul class="breadcrumbs">
                <li><a href="{{ cms_path() }}">All Blocks</a></li>
                <li>
                    <span class="show-for-sr">Current: </span> {{$title}}
                </li>
            </ul>
        </nav>
        <cms-viewer path="{{ $title }}" get="{{ cms_api_path($title) }}" post="{{ cms_path() }}"></cms-viewer>
    </section>
@stop
