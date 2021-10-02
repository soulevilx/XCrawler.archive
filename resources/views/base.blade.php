<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'XCrawler')</title>

    @include('resources.css')
    @yield('css')
</head>
<body class="antialiased">
<div class="wrapper">
    @section('sidebar')
        @include('blocks.sidebar')
    @show
    <div class="main">
        @yield('topbar')
        <div class="container-fluid mt-4">
            @yield('messages')
        </div>

        <div class="container-fluid">
            @yield('navbar')
        </div>
        <main class="content">
            @yield('content')
        </main>
        @yield('footer')
    </div>
</div>

@include('resources.js')
@yield('js')
</body>
</html>
