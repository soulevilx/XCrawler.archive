@if(!empty($gallery))
    <div class="container-fluid">
        <div class="row">
            @foreach ($movie->r18->gallery as $image)
                <div class="col-lg-6">
                    <img
                        src="{{$image['large']}}"
                        alt="{{$movie->title}}"

                        class="w-100 shadow-1-strong rounded mb-4"/>
                </div>
            @endforeach
        </div>
    </div>
@endif
