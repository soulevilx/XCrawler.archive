@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <form action="{{route('configurations.update')}}" method="post">
                <div class="row">
                @csrf <!-- {{ csrf_field() }} -->
                    @foreach($configurations as $configuration)
                        <div class="col">
                            <h4>{{ucfirst($configuration->name)}}</h4>
                            @foreach($configuration->settings as $key => $value)
                                <div class="form-group row">
                                    <label for="input{{$key}}" class="col-sm-2 col-form-label">{{$key}}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input{{$key}}" name="{{$configuration->name}}[{{$key}}]"
                                               value="{{$value}}"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
