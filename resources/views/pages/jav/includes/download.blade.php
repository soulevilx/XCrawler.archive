<form action="{{route('onejav.download', $onejav)}}" method="post">
    <button type="submit" class="btn btn-primary has-tooltip" data-toggle="tooltip" data-placement="bottom"
            title="{{\Illuminate\Support\Facades\Config::get('services.jav.download_dir')}}"><i
            class="fas fa-download"></i></button>
</form>
