<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <a class="navbar-brand" href="#">{{$title ?? ''}}</a>
        <form class="form-inline my-2 my-lg-0 mr-2" action="{{route('movies.index' )}}" method="get">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="onejav" name="whereHas[]" {{in_array('onejav',app('request')->input('whereHas', [])) ? 'checked' : ''}}>
                <label class="form-check-label" for="inlineCheckbox1">Onejav</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="r18" name="whereHas[]" {{in_array('r18',app('request')->input('whereHas', [])) ? 'checked' : ''}}>
                <label class="form-check-label" for="inlineCheckbox2">R18</label>
            </div>

            <select class="form-control form-control-sm mr-2" name="orderBy">
                @include('includes.bootstrap.option', ['value' => 'created_at', 'default'=> true, 'created_at', 'name' => 'orderBy', 'text' => 'Created at'])
                @include('includes.bootstrap.option', ['value' => 'updated_at', 'name' => 'orderBy', 'text' => 'Updated at'])
                @include('includes.bootstrap.option', ['value' => 'release_date', 'name' => 'orderBy', 'text' => 'Release date'])
            </select>

            <select class="form-control form-control-sm mr-2" name="orderDir">
                @include('includes.bootstrap.option', ['value' => 'asc', 'name' => 'orderDir', 'text' => 'ASC'])
                @include('includes.bootstrap.option', ['value' => 'desc', 'default'=> true, 'name' => 'orderDir', 'text' => 'DESC'])
            </select>

            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="Search" aria-label="Search"
                   name="keyword" value="{{app('request')->input('keyword') ?? null}}">

            <div class="input-group-append float-right text-right">
                <button class="btn btn-primary form-control-sm my-2 my-sm-0" type="submit"><i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </nav>
</div>
