<div class="card">
    <a href="{{route('movie.show', $movie)}}"><img src="{{$movie->cover}}" class="card-img-top lazy"
                                                   alt="{{$movie->dvd_id}}" width="70%"></a>
    <div class="card-body">
        <h5 class="card-title">{{$movie->dvd_id}}</h5>
        <p class="card-text">{{$movie->description}}.</p>
    </div>
    <div class="card-footer">
        <div class="btn-group" role="group" aria-label="Basic example">
            @isset($showWordPress)
                @include('jav.blocks.elements.wordpress', ['movie' => $movie])
            @endisset
            @isset($showDownload)
                @include('jav.blocks.elements.download', ['onejav' => $movie->onejav])
            @endisset
        </div>
    </div>
</div>
