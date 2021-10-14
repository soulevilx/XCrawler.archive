@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-xxl-5 d-flex">
                    <div class="w-100">
                        <div class="row">
                            @foreach($data as $key => $values)
                                <div class="col-sm-6">
                                    <div class="card">
                                        @include('includes.block', ['title' => $key, 'total' => $values['total'], 'percent' =>$values['percent'], 'init' => $values['init']])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-xxl-7">
                    <div class="card flex-fill w-100">
                        <div class="card-header">

                            <h5 class="card-title mb-0">Recent Movement</h5>
                        </div>
                        <div class="card-body py-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
