[title {{ $movie->dvd_id }}]
[category Adult,JAV]
[tags {{ implode(', ', $movie->genres()->pluck('name')->toArray()) }}, {{ implode(', ', $movie->performers()->pluck('name')->toArray()) }}]
[publicize off]
[excerpt]{{ $movie->description }}[/excerpt]
[status draft]
<p>
    <img src="{{ $movie->cover }}" rel="nofollow" alt="{{$movie->dvd_id}}"/>
</p>

<quote>{{ $movie->description }}</quote>

<ul>
    <li><strong>Idols:</strong> {{ implode(', ', $movie->performers()->pluck('name')->toArray()) }}</li>
    <li><strong>Content ID:</strong> {{ $movie->content_id }}</li>
    <li><strong>Downloadable:</strong> {{ $movie->is_downloadable ? 'YES' : '' }}</li>
    <li><strong>Director:</strong> {{ $movie->director }}</li>
    <li><strong>Studio:</strong> {{ $movie->studio }}</li>
    <li><strong>Label:</strong> {{ $movie->label }}</li>
    @if($movie->channels)
        <li><strong>Channels:</strong> {{ implode(', ', $movie->channels) }}</li>
    @endif
</ul>

[more]
@if(!empty($movie->gallery))
    @foreach ($movie->gallery as $image)
        <img src="{{$image['large']}}" alt="{{$movie->title}}"/>
    @endforeach
@endif

<p>
    @if($movie->onejav)
        <a href="https://onejav.com{{ $movie->onejav->url }}" rel="nofollow">Onejav</a>
    @endif

    @if($movie->r18)
        <a href="{{ $movie->r18->url }}" rel="nofollow">R18</a>
    @endif
</p>
