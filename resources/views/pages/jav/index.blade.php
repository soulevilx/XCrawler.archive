@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="card-columns">
                @foreach($movies as $movie)
                    <div class="col mb-4">
                        @include('pages.jav.layouts.movie', ['showDownload' => true, 'showWordPress' => true])
                    </div>
                @endforeach
            </div>
        </div>
        @include('includes.pagination', ['pagination' => $movies->links()])
    </main>
@endsection
