<div class="card">
    <div class="card-header"><i class="fas fa-user-friends"></i> Performers</div>
    <ul class="list-group list-group-flush">
        @foreach($performers as $performer)
            <li class="list-group-item">
                <a href="{{route('movies.index', ['performers' => [$performer->name]])}}">{{$performer->name}}</a>
            </li>
        @endforeach
    </ul>
</div>
