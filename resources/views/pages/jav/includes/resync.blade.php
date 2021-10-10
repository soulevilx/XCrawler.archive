@if(!is_null($movie->r18))
    <form action="{{route('movie.resync', $movie)}}" method="post">
    @csrf <!-- {{ csrf_field() }} -->
        <button type="submit" class="btn btn-warning"><i class="fas fa-sync"></i></button>
    </form>
@endif
