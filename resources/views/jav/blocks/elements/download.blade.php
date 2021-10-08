@if(!is_null($onejav))
    <form action="{{route('onejav.download', $onejav)}}" method="post">
    @csrf <!-- {{ csrf_field() }} -->
        <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i></button>
    </form>
@endif
