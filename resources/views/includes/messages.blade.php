@if(session()->get('message'))
    <div class="container-fluid mt-4">
        <div class="alert alert-{{session()->get('message')['type']}} alert-dismissible fade show" role="alert">
            {!! session()->get('message')['message'] !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
