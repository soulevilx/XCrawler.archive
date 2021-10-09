@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    @include('pages.jav.layouts.movie', ['showControls' => false])
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            @include('pages.jav.includes.genres', ['genres' => $movie->genres])
                        </div>
                        <div class="col-md-12">
                            @include('pages.jav.includes.genres', ['genres' => $movie->performers])
                        </div>
                        <div class="col-md-12">
                            @include('pages.jav.includes.controls', ['movie' => $movie])
                        </div>
                    </div>
                </div>

            </div>
            @if(!is_null($movie->onejav))
                <div class="container-fluid">
                    @include('pages.jav.layouts.onejav')
                </div>
            @endif
            @if(!is_null($movie->r18))
                <div class="container-fluid mb-4">
                    <div class="row">
                        <video src="{{$movie->r18?->sample()}}" controls></video>
                    </div>
                </div>
                @include('pages.jav.includes.gallery', ['gallery' => $movie->r18])
            @endif
        </div>
    </main>
@endsection
