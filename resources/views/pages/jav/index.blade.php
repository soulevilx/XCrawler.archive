@extends('layouts.base')
@section('navbar')
    @include('includes.navbar')
@endsection
@section('content')
    <div class="container-fluid">
        @foreach(app('request')->input('genres', []) as $genre)
            <span class="badge badge-secondary">{{$genre}}</span>
        @endforeach
            @foreach(app('request')->input('performers', []) as $performer)
                <span class="badge badge-dark">{{$performer}}</span>
            @endforeach
    </div>
    <main class="content">
        <div class="container-fluid">
            <div class="card-columns">
                @foreach($movies as $movie)
                    <div class="col mb-4">
                        @include('pages.jav.layouts.movie', ['showControls' => true])
                    </div>
                @endforeach
            </div>
        </div>
        @include('includes.pagination', ['pagination' => $movies->links()])
    </main>
@endsection
