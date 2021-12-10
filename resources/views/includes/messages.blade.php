@php
    $messages = session()->get('messages')
@endphp
@if(!empty($messages))
    <div class="container-fluid mt-4">
        @foreach($messages as $message)
            <div class="alert alert-{{$message['type']}} alert-dismissible fade show" role="alert">
                {!! $message['message'] !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    </div>
@endif
