<form action="{{route('movie.to-wordpress', $movie)}}" method="post">
@csrf <!-- {{ csrf_field() }} -->
    <button type="submit" class="btn btn-primary mr-4"><i class="fab fa-wordpress"></i></button>
</form>
