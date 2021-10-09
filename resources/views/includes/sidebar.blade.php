<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        @section('logo')
            @include('includes.logo')
        @show
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Jav
            </li>

            <li class="sidebar-item active">
                <a class="sidebar-link" href="{{route('movies.index')}}">
                    <i class="fas fa-video"></i><span class="align-middle">Movies @include('includes.bootstrap.badge', ['text' => \App\Jav\Models\Movie::count(), 'isPill' => true])</span>
                </a>
            </li>

        </ul>
        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <strong class="d-inline-block mb-2">Upgrade to Pro</strong>
                <div class="mb-3 text-sm">
                    Are you looking for more components? Check out our premium version.
                </div>
                <div class="d-grid">
                    <a href="upgrade-to-pro.html" class="btn btn-primary">Upgrade to Pro</a>
                </div>
            </div>
        </div>
    </div>
</nav>
