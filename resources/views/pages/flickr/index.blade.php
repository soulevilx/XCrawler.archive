@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            @isset($user)
                {{$user['id']}}
            @endisset
        </div>
        <div class="row">
            <div class="col-6">
                @include('includes.download', ['route' => route('flickr.albums.download'), 'title' => 'Download albums', 'description' => 'Enter Profile\' url.'])
            </div>

            <div class="col-6">
                @include('includes.download', ['route' => route('flickr.album.download'), 'title' => 'Download specific Album', 'description' => 'Enter Flickr Album\' url.'])
            </div>
        </div>
    </main>
@endsection
