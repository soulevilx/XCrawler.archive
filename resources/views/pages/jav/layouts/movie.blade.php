<!-- Movie layout -->
<div class="card">
    <a href="{{route('movie.show', $movie)}}">
        <img src="https://via.placeholder.com/150" class="card-img-top lazy" data-src="{{$movie->cover}}" alt="{{$movie->dvd_id}}" width="70%"/>
    </a>
    <div class="card-body">
        <h5 class="card-title">{{$movie->dvd_id}}</h5>
        <p class="card-text">{{$movie->description}}</p>
    </div>
    <div class="card-footer">
       @include('pages.jav.includes.controls', ['movie' => $movie])
    </div>
</div>
