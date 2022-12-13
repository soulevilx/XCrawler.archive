@extends(backpack_view('blank'))
@section('content')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#"></a>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

            </ul>
            <form class="form-inline my-2 my-lg-0" action="{{route(Route::currentRouteName())}}">
                @include('includes.checkbox', ['values' => ['r18', 'onejav'], 'key' => 'has[]'])
                @include('includes.select', ['values' => ['created_at', 'dvd_id'], 'key' => 'orderBy'])

                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline my-2 my-sm-2" type="submit">Search</button>
            </form>

        </div>
    </nav>

    <div class="row">
        <div class="card-columns">
            @foreach($movies as $movie)
                <div class="col-mb-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    {{$movie->getContentId()}} - <span
                                        class="badge badge-dark">{{$movie->getDvdId()}}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-left float-right"><i class="far fa-calendar mr-2"></i>{{ $movie->created_at }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="">
                            <img src="{{$movie->getCover()}}" class="card-img-top lazy"
                                 alt="{{$movie->getContentId()}}">
                        </a>
                        <div class="card-body">

                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="">
                                    <form action="{{route('movies.download', $movie)}}" method="post">
                                        <button type="submit" class="btn btn-primary has-tooltip" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="{{\Illuminate\Support\Facades\Config::get('services.jav.download_dir')}}">
                                            <i
                                                class="fas fa-download"></i></button>
                                        <a href="#" class="badge badge-secondary"></a>
                                        <span class="badge badge-dark"></span>
                                        @csrf
                                    </form>
                                </div>

                                <div class="btn-group mr-2" role="group" aria-label="">
                                    <form action="" method="post">
                                        <button type="submit" class="btn btn-warning"><i
                                                class="fas fa-cloud-download-alt"></i></button>
                                    </form>
                                </div>


                                <div class="btn-group mr-2" role="group" aria-label="">

                                </div>

                                @if ($movie->r18)
                                    <div class="btn-group mr-2 float-right text-right" role="group"
                                         aria-label="Second group">
                                        <img src="https://www.r18.com/assets/image/common/logo.svg"/>
                                    </div>
                                @endif
                            </div>

                        </div>
                        @if($movie instanceof \App\Jav\Models\Movie)
                            @if(!empty($movie->performers))
                                <div class="card-footer text-muted">
                                    <div class="row">
                                        @foreach ($movie->performers as $performer)

                                            <span class="badge badge-secondary">{{$performer->name}}</span>

                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if(!empty($movie->genres))
                                <div class="card-footer text-muted">
                                    <div class="row">
                                        @foreach ($movie->genres as $genre)
                                            <span class="badge badge-secondary">{{$genre->name}}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            @if(!empty($movie->getPerformers()))
                                <div class="card-footer text-muted">
                                    <div class="row">
                                        @foreach ($movie->getPerformers() as $performer)
                                            @if($performer instanceof \App\Jav\Models\Performer)
                                                <span class="badge badge-secondary">{{$performer->name}}</span>
                                            @else
                                                <span class="badge badge-secondary">{{$performer}}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if(!empty($movie->getGenres()))
                                <div class="card-footer text-muted">
                                    <div class="row">
                                        @foreach ($movie->getGenres() as $genre)
                                            @if($genre instanceof \App\Jav\Models\Genre)
                                                <span class="badge badge-secondary">{{$genre->name}}</span>
                                            @else
                                                <span class="badge badge-secondary">{{$genre}}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{ $movies->withQueryString()->links() }}
@endsection
