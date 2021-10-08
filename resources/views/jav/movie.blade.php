@extends('base')

@include('blocks.topbar')
@isset($messages)
@section('messages')
    @include('blocks.messages')
@endsection
@endif
@section('content')
    <div class="row">
        <div class="col-md-6">
            @include('jav.blocks.movie')
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-tags"></i> Genres</div>
                        <ul class="list-group list-group-flush">
                            @foreach($movie->genres as $genre)
                                <li class="list-group-item"><a
                                        href="{{route('movies.index', ['genres' => [$genre->name]])}}">{{$genre->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-user-friends"></i> Performers</div>
                        <ul class="list-group list-group-flush">
                            @foreach($movie->performers as $performer)
                                <li class="list-group-item">{{$performer->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        @include('jav.blocks.elements.download', ['onejav' => $movie->onejav])
                        @include('jav.blocks.elements.wordpress', ['movie' => $movie])
                    </div>
                </div>
            </div>
        </div>

    </div>
    @if(!is_null($movie->onejav))
        <div class="container-fluid">
            @include('jav.blocks.onejav')
        </div>
    @endif
    @if(!is_null($movie->r18))
        <div class="container-fluid mb-4">
            <div class="row">
                <video src="{{$movie->r18?->sample()}}" controls></video>
            </div>
        </div>
          @include('jav.blocks.elements.gallery', ['gallery' => $movie->r18])
    @endif
@endsection
@include('blocks.footer')
