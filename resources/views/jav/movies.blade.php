@extends('base')
@include('blocks.topbar')
@isset($messages)
@section('messages')
    @include('blocks.messages')
@endsection
@endif
@section('navbar')
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Jav</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mr-4">
                        <span class="badge badge-pill badge-primary">{{\App\Jav\Models\Movie::count()}}</span>
                    </li>
                    <li class="nav-item mr-4">
                        <span class="badge badge-pill badge-primary">{{\App\Jav\Models\Onejav::count()}}</span>
                    </li>
                    <li class="nav-item mr-4">
                        <span class="badge badge-pill badge-primary">{{\App\Jav\Models\R18::count()}}</span>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="{{route('movies.index' )}}" method="get">
                @csrf <!-- {{ csrf_field() }} -->
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"
                           name="keyword">

                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card-columns">
            @foreach($movies as $movie)
                <div class="col mb-4">
                    @include('jav.blocks.movie')
                </div>
            @endforeach
        </div>
    </div>
    <div class="container-fluid">
        {{ $movies->links() }}
    </div>
@endsection
@include('blocks.footer')
