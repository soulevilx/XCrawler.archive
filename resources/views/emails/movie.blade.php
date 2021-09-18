[title {{ $movie->dvd_id }}]
[category Adult,JAV]
[tags {{ $genres }}, {{ $performers }}]
[publicize off]
[excerpt]{{ $movie->description }}[/excerpt]
[status draft]
<p>
    <img src="{{ $movie->cover }}" rel="nofollow" alt="{{$movie->dvd_id}}"/>
</p>

<quote>{{ $movie->description }}</quote>

<ul>
    <li><strong>Performers:</strong> {{ $performers }}</li>
    <li><strong>Genres:</strong> {{ $genres }}</li>
    <li><strong>Content ID:</strong> {{ $movie->content_id }}</li>
    <li><strong>DVD ID:</strong> {{ $movie->dvd_id }}</li>
    <li><strong>Downloadable:</strong> {{ $movie->isDownloadable() ? 'YES' : '' }}</li>
    @if($movie->director)
        <li><strong>Director:</strong> {{ $movie->director }}</li>
    @endif
    @if($movie->studio)
        <li><strong>Studio:</strong> {{ $movie->studio }}</li>
    @endif
    @if($movie->label)
        <li><strong>Label:</strong> {{ $movie->label }}</li>
    @endif
    @if($movie->channels)
        <li><strong>Channels:</strong> {{ $channels }}</li>
    @endif

</ul>

[more]

@if($onejav)
    <ul>
        <li><strong>Size:</strong> {{ $onejav->size }}</li>
    </ul>
    <p>
        <a href="https://onejav.com{{ $onejav->url }}" rel="nofollow">Onejav</a>
    </p>
@endif

@if($r18)
    <p>
        @if($sample)
            <video src="{{ $sample }}"></video>
        @endif
    </p>

    <p>
        @if(!empty($movie->gallery))
            @foreach ($movie->gallery as $image)
                <img src="{{$image['large']}}" alt="{{$movie->title}}"/>
            @endforeach
        @endif
    </p>
    <p>
        <a href="{{ $r18->url }}" rel="nofollow">R18</a>
    </p>
@endif
