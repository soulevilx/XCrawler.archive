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
                        <form action="{{route('onejav.download', $movie->onejav)}}" method="post">

                        @csrf <!-- {{ csrf_field() }} -->
                            <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i></button>
                            <small id="emailHelp"
                                   class="form-text text-muted">{{ Config::get('services.jav.download_dir') }}</small>

                        </form>
                        <form action="{{route('movie.to-wordpress', $movie)}}" method="post">
                        @csrf <!-- {{ csrf_field() }} -->
                            <button type="submit" class="btn btn-primary mr-4"><i class="fab fa-wordpress"></i></button>
                        </form>
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
        <div class="container-fluid">
            @if(!empty($movie->r18->gallery))
                @foreach ($movie->r18->gallery as $image)
                    <img src="{{$image['large']}}" alt="{{$movie->title}}"/>
                @endforeach
            @endif
        </div>
    @endif
@endsection
@include('blocks.footer')
