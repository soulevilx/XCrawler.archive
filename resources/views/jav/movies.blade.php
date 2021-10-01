@extends('base')
@include('subs.topbar')
@isset($messages)
@section('messages')
    @include('subs.messages')
@endsection
@endif
@section('content')
    <div class="container-fluid">
        <div class="card-columns">
            @foreach($movies as $movie)
                <div class="col mb-4">
                    @include('jav.subs.movie')
                </div>
            @endforeach
        </div>
    </div>
    <div class="container-fluid">
        {{ $movies->links() }}
    </div>
@endsection
@include('subs.footer')
