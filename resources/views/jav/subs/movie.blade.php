<div class="card">
    <a href="{{route('movie.show', $movie)}}"><img src="{{$movie->cover}}" class="card-img-top lazy" alt="{{$movie->dvd_id}}" width="70%"></a>
    <div class="card-body">
        <h5 class="card-title">{{$movie->dvd_id}}</h5>
        <p class="card-text">{{$movie->description}}.</p>
    </div>
    <div class="card-footer">
        <div class="btn-group" role="group" aria-label="Basic example">
        <form action="{{route('movie.to-wordpress', $movie)}}" method="post">
        @csrf <!-- {{ csrf_field() }} -->
            <button type="submit" class="btn btn-primary mr-4"><i class="fab fa-wordpress"></i></button>
        </form>
           @if(!is_null($movie->onejav))
        <form action="{{route('onejav.download', $movie->onejav)}}" method="post">
        @csrf <!-- {{ csrf_field() }} -->
            <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i></button>
        </form>
                @endif
        </div>
    </div>
</div>
