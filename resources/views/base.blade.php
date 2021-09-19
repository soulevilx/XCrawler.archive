<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>


    <!-- Fonts -->
    @section('css')
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
        <link href="https://demo-basic.adminkit.io/css/app.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"/>
    @show

</head>
<body class="antialiased">
<div class="wrapper">
    @section('sidebar')
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                @yield('logo')

                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Jav
                    </li>

                    <li class="sidebar-item active">
                        <a class="sidebar-link" href="index.html">

                            <i class="fas fa-video"></i> <span
                                class="align-middle">Movies</span>
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
    @show
    <div class="main">
        @yield('topbar')
        <main class="content">
            @yield('content')
        </main>
        @yield('footer')
    </div>
</div>

<script src="https://demo-basic.adminkit.io/js/app.js"></script>
</body>
</html>
