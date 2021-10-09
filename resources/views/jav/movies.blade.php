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
                        <div class="position-relative">
                            <i class="fas fa-video"></i>
                            <span class="indicator" style="right: -18px; top: -6px">{{\App\Jav\Models\Movie::count()}}</span>
                        </div>
                        <span class="badge badge-pill badge-primary"></span>
                    </li>

                </ul>
                <form class="form-inline my-2 my-lg-0" action="{{route('movies.index' )}}" method="get">
                @csrf <!-- {{ csrf_field() }} -->
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"
                           name="keyword">
                    <div class="input-group-append">
                        <button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fas fa-search mr-2"></i>Search</button>
                    </div>

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
                    @include('jav.blocks.movie', ['showDownload' => true, 'showWordPress' => true])
                </div>
            @endforeach
        </div>
    </div>
    <div class="container-fluid">
        {{ $movies->links() }}
    </div>
@endsection
@include('blocks.footer')
