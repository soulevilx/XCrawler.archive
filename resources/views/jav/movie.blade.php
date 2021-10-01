@extends('base')

@section('logo')
    <a class="sidebar-brand" href="">
        <span class="align-middle">XCrawler</span>
    </a>
@endsection
@include('subs.topbar')
@isset($messages)
@section('messages')
    @include('subs.messages')
@endsection
@endif
@section('content')
    <div class="card">
        <img src="{{$movie->cover}}" class="card-img-top" alt="{{$movie->dvd_id}}" width="70%">
        <div class="card-body">
            <h5 class="card-title">{{$movie->dvd_id}}</h5>
            <p class="card-text">{{$movie->description}}.</p>
            <p class="card-text float-right"><small class="text-muted">{{$movie->created_at}}</small></p>
        </div>
        <div class="card-footer">
            <form action="{{route('movie.to-wordpress', $movie)}}" method="post">
            @csrf <!-- {{ csrf_field() }} -->
                <button type="submit" class="btn btn-primary"><i class="fab fa-wordpress"></i></button>
            </form>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card-deck">
            <div class="card">
                <img src="{{$movie->onejav->cover}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Onejav</h5>
                </div>
                <div class="card-footer">
                    <form action="{{route('onejav.download', $movie->onejav)}}" method="post">
                    @csrf <!-- {{ csrf_field() }} -->
                        <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i></button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@include('subs.footer')
