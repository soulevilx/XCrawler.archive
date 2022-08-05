@extends(backpack_view('blank'))
@section('content')
    <div class="row">
        <div class="card-columns">
            @foreach($repositories as $name => $repository)
                <div class="col-mb-4">
                    <div class="card">
                        <div class="card-header">
                            {{$name}}
                        </div>
                        <a href="">
                            <img src="{{$repository->latest()->first()->getCover()}}" class="card-img-top lazy"
                                 alt="TSMS-040" width="70%" style="">
                        </a>
                        <div class="card-body">

                        </div>
                        <div class="card-footer">
                            <ul class="list-group list-group-flush">
                                <div class="btn btn-primary float-left">
                                    <span class="badge badge-light">{{ $repository->total() }}</span>
                                </div>
                            </ul>
                        </div>
                        <div class="card-footer text-muted">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-left"><i class="far fa-calendar mr-2"></i>{{$repository->totalToday()}} items updated today</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-left float-right"><i class="far fa-calendar mr-2"></i>{{\Carbon\Carbon::now()}}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
