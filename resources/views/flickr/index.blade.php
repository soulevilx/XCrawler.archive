@extends('base')
@include('blocks.topbar')
@isset($messages)
@section('messages')
    @include('blocks.messages')
@endsection
@endif
@section('navbar')

@endsection
@section('content')
    <div class="container-fluid">
        @isset($user)
            {{$user['id']}}
        @endisset
    </div>
    <form action="{{route('flickr.album.download')}}" method="post">
    @csrf <!-- {{ csrf_field() }} -->
        <div class="form-group">
            <label for="exampleInputEmail1">Album URL</label>
            <input type="text" name="url" class="form-control" id="url" aria-describedby="urlHelp">
            <small id="emailHelp" class="form-text text-muted">Enter Flickr Album' url.</small>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
@include('blocks.footer')
