<div class="card">
    <div class="card-header"><i class="fas fa-tags"></i> Genres</div>
    <ul class="list-group list-group-flush">
        @foreach($genres as $genre)
            <li class="list-group-item">
                <a href="{{route('movies.index', ['genres' => [$genre->name]])}}">{{$genre->name}}</a>
            </li>
        @endforeach
    </ul>
</div>
