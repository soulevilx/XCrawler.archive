<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'XCrawler')</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="{{asset('vendor/adminkit/css/app.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/fontawesome/css/all.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css/')}}" rel="stylesheet"/>
<!-- <link href="{{asset('vendor/mdb5/css/mdb.dark.min.css')}}" rel="stylesheet"/> -->

    @yield('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
</head>
<body class="antialiased">
<div class="wrapper">
    @section('sidebar')
        @include('includes.sidebar')
    @show
    <div class="main">
        @section('topbar')
            @include('includes.topbar')
        @show

        @section('messages')
            @include('includes.messages')
            @include('includes.confirm', ['confirm', $confirm ?? null])
        @show

        @yield('navbar')

        @yield('content')

        @section('footer')
            @include('includes.footer')
        @show
    </div>
</div>

<script src="{{asset('vendor/js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>

<script src="{{asset('vendor/adminkit/js/app.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>

<script>
    jQuery(function () {
        jQuery('.lazy').Lazy();
        jQuery('.has-tooltip').tooltip({})
        jQuery('form').append('{{csrf_field()}}');
    });
</script>

@yield('js')
</body>
</html>
