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
                            <div class="shadow p-3 mb-5 bg-white rounded">
                                <div class="list-group">
                                    <div class="list-group-item list-group-item-action active">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Released</h5>
                                            <!--<small>3 days ago</small>-->
                                        </div>
                                        <p class="mb-1">{{$movie->release_date?->format('Y-m-d')}}</p>
                                        <!--<small>And some small print.</small>-->
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Dvd ID</h5>
                                            <!--<small>3 days ago</small>-->
                                        </div>
                                        <p class="mb-1">{{$movie->dvd_id}}</p>
                                        <!--<small>And some small print.</small>-->
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Content ID</h5>
                                            <!--<small>3 days ago</small>-->
                                        </div>
                                        <p class="mb-1">{{$movie->content_id}}</p>
                                        <!--<small>And some small print.</small>-->
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Studio</h5>
                                            <!--<small>3 days ago</small>-->
                                        </div>
                                        <p class="mb-1">{{$movie->studio}}</p>
                                        <!--<small>And some small print.</small>-->
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Series</h5>
                                            <!--<small>3 days ago</small>-->
                                        </div>
                                        <p class="mb-1">{{$movie->series()}}</p>
                                        <!--<small>And some small print.</small>-->
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Director</h5>
                                            <!--<small>3 days ago</small>-->
                                        </div>
                                        <p class="mb-1">{{$movie->director}}</p>
                                        <!--<small>And some small print.</small>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            @include('pages.jav.includes.controls', ['movie' => $movie])
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">

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
