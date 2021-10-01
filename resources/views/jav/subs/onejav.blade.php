<div class="card-deck">
    <div class="card">
        <img src="{{$movie->onejav->cover}}" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Onejav</h5>
        </div>
        <div class="card-footer">
            <form action="{{route('onejav.download', $movie->onejav)}}" method="post">
            @csrf <!-- {{ csrf_field() }} -->
                <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i></button>
            </form>
        </div>
    </div>
</div>
