<div class="card-columns">
    <div class="card">
        <a href="https://onejav.com{{$movie->onejav->url}}" target="_blank">
            <img src="{{$movie->onejav->cover}}" class="card-img-top" alt="{{$movie->onejav->dvd_id}}"></a>
        <div class="card-body">
            <h5 class="card-title">Onejav</h5>
        </div>
        @isset($showDownload)
            <div class="card-footer">
                @include('pages.jav.includes.download');
            </div>
        @endisset
    </div>
</div>
