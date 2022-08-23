<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@includeWhen(class_exists(\Backpack\DevTools\DevToolsServiceProvider::class), 'backpack.devtools::buttons.sidebar_item')

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('movies') }}"><i class="fa-solid fa-film"></i> <span>Movies</span></a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa-solid fa-lock"></i> JAV</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('onejav') }}"><span>Onejav</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('r18') }}"><span>R18</span></a></li>
    </ul>
</li>

<hr />
