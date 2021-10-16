<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group mr-2" role="group" aria-label="Second group">
        @include('pages.jav.includes.wordpress', ['movie' => $movie])
    </div>
    @if(!is_null($movie->onejav))
        <div class="btn-group mr-2" role="group" aria-label="First group">
            @include('pages.jav.includes.download', ['onejav' => $movie->onejav])
        </div>
    @else
        <div class="btn-group mr-2" role="group" aria-label="First group">
            @include('pages.jav.includes.requestdownload', ['movie' => $movie])
        </div>
    @endif
    @if(!is_null($movie->r18))
        <div class="btn-group mr-2" role="group" aria-label="First group">
            @include('pages.jav.includes.resync', ['movie' => $movie])
        </div>
        <div class="btn-group mr-2 float-right text-right" role="group" aria-label="Second group">
            <img src="https://www.r18.com/assets/image/common/logo.svg"/>
        </div>
    @endif
</div>
