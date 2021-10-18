@extends('layouts.base')
@section('navbar')
@endsection
@section('content')
    <main class="content">
        <div class="container-fluid">
            @isset($user)
                {{$user['id']}}
            @endisset
        </div>
        <form action="{{route('flickr.album.download')}}" method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">Album URL</label>
                <input type="text" name="url" class="form-control" id="url" aria-describedby="urlHelp">
                <small id="emailHelp" class="form-text text-muted">Enter Flickr Album' url.</small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </main>
@endsection
