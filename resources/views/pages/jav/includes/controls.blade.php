<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group mr-2" role="group" aria-label="First group">
        @include('pages.jav.includes.download', ['onejav' => $movie->onejav])
    </div>
    <div class="btn-group mr-2" role="group" aria-label="Second group">
        @include('pages.jav.includes.wordpress', ['movie' => $movie])
    </div>
</div>
