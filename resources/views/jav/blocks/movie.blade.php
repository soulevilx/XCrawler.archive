<div class="card">
    <a href="{{route('movie.show', $movie)}}"><img  src="https://via.placeholder.com/150" class="card-img-top lazy" data-src="{{$movie->cover}}"
                                                   alt="{{$movie->dvd_id}}" width="70%"/></a>
    <div class="card-body">
        <h5 class="card-title">{{$movie->dvd_id}}</h5>
        <p class="card-text">{{$movie->description}}</p>
    </div>
    <div class="card-footer">
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group mr-2" role="group" aria-label="First group">
                @isset($showDownload)
                    @include('jav.blocks.elements.download', ['onejav' => $movie->onejav])
                @endisset
            </div>
            @isset($showWordPress)
                <div class="btn-group mr-2" role="group" aria-label="Second group">
                    @include('jav.blocks.elements.wordpress', ['movie' => $movie])
                </div>
            @endisset

        </div>
    </div>
</div>
