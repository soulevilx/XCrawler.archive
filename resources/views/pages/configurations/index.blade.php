@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <form action="{{route('configurations.update')}}" method="post">
                <button type="submit" class="btn btn-primary mb-4"><i class="fas fa-save mr-2"></i>Save</button>
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Application Settings</h5>
                        </div>

                        <div class="list-group list-group-flush" role="tablist">
                            @foreach($configurations as $configuration)
                                <a class="list-group-item list-group-item-action {{$configuration->name ==='onejav' ? 'active': ''}}"
                                   data-bs-toggle="list" href="#{{$configuration->name}}" role="tab">
                                    {{$configuration->name}}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-9">

                        <div class="tab-content">
                            @foreach($configurations as $configuration)
                                <div
                                    class="tab-pane fade active {{$configuration->name ==='onejav' ? 'show active': ''}}"
                                    id="{{$configuration->name}}">
                                    @foreach($configuration->settings as $key => $value)
                                        <div class="form-group row">
                                            <label for="input{{$key}}" class="col-sm-2 col-form-label">{{$key}}</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="input{{$key}}"
                                                       name="{{$configuration->name}}[{{$key}}]"
                                                       value="{{$value}}"/>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>


                </div>
            </div>
            </form>
        </div>
    </main>
@endsection
