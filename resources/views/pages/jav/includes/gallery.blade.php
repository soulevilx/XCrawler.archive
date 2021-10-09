@if(!empty($gallery))
    <div class="container-fluid">
        <div class="row">
            @foreach ($movie->r18->gallery as $image)
                <div class="col-lg-6">
                    @if(array_key_exists('large', $image))
                        <img
                            src="https://via.placeholder.com/150"
                            alt="{{$movie->title}}"
                            data-src="{{$image['large']}}"
                            class="w-100 shadow-1-strong rounded mb-4 lazy"/>
                    @else
                        <img
                            src="https://via.placeholder.com/150"
                            alt="{{$movie->title}}"
                            data-src="{{$image}}"
                            class="w-100 shadow-1-strong rounded mb-4 lazy"/>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
