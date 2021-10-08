[title {{ $movie->dvd_id }}]
[category Adult,JAV]
[tags {{ $genres }}, {{ $performers }}]
[publicize {{$publicize ?? 'off'}}]
[excerpt]{{ $movie->description }}[/excerpt]
[status {{$status ?? 'draft'}}]

<!-- wp:image {"id":{{$movie->id}},"sizeSlug":"large","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-large is-style-default">
    <img src="{{$movie->cover}}" alt="" class="wp-image-{{$movie->id}}"/>
    <figcaption>{{$movie->dvd_id}}</figcaption>
</figure>
<!-- /wp:image -->

<blockquote class="wp-block-quote"><p>{{ $movie->description }}</p></blockquote>

<!-- wp:list -->
<ul>
    <li><strong>Performers:</strong> {!! $performers !!}</li>
    <li><strong>Genres:</strong> {!! $genres !!}</li>
    <li><strong>Content ID:</strong> {{ $movie->content_id }}</li>
    <li><strong>DVD ID:</strong> {{ $movie->dvd_id }}</li>
    @if($onejav)
        <li><strong>Downloadable:</strong> <a href="https://onejav.com{{ $onejav->url }}" rel="nofollow">Onejav</a></li>
        <li><strong>Size:</strong> {{ $onejav->size }}</li>
    @endif
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
<!-- /wp:list -->

[more]

@if($r18)
    <p>
        @if($r18->sample())
            <video src="{{ $r18->sample() }}"></video>
        @endif
    </p>
    @if(!empty($movie->r18->gallery))
        <figure class="wp-block-gallery columns-3 is-cropped">
            @foreach ($movie->r18->gallery as $image)
                <p>
                    <img src="{{$image['large']}}" alt="{{$movie->title}}"/>
                </p>
            @endforeach
        </figure>
    @endif
@endif
