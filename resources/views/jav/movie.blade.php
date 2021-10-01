@extends('base')

@include('subs.topbar')
@isset($messages)
@section('messages')
    @include('subs.messages')
@endsection
@endif
@section('content')
    @include('jav.subs.movie')
    @if(!is_null($movie->onejav))
    <div class="container-fluid">
        @include('jav.subs.onejav')
    </div>
    @endif
@endsection
@include('subs.footer')
