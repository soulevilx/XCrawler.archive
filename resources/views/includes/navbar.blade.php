<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <a class="navbar-brand" href="#">{{$title ?? ''}}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <form class="form-inline my-2 my-lg-0" action="{{route('movies.index')}}" method="get">
            <input type="hidden" name="downloadable[]" value="true">
            <button class="btn btn-info btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-cloud-download-alt mr-2"></i>OneJav</button>
        </form>
        <form class="form-inline my-2 my-lg-0 ml-2" action="{{route('movies.index')}}" method="get">
            <input type="hidden" name="whereHas[]" value="r18">
            <button class="btn btn-info btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-ticket-alt mr-2"></i>R18</button>
        </form>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            </ul>
            <form class="form-inline my-2 my-lg-0" action="{{route('movies.index' )}}" method="get">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"
                       name="keyword">
                <div class="input-group-append">
                    <button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </nav>
</div>