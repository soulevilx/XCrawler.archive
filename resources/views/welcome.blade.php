@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-xxl-5 d-flex">
                    <div class="w-100">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    @include('layouts.block', ['title' => 'R18', 'total' => $total['r18'], 'inc' =>$inc['r18']])

                                </div>
                                <div class="card">

                                    @include('layouts.block', ['title' => 'OneJav', 'total' => $total['onejav'], 'inc' =>$inc['onejav']])

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">

                                    @include('layouts.block', ['title' => 'XCity Idols', 'total' => $total['xcityIdols'], 'inc' =>$inc['xcityIdols']])

                                </div>
                                <div class="card">
                                    @include('layouts.block', ['title' => 'XCity Videos', 'total' => $total['xcityVideos'], 'inc' =>$inc['xcityVideos']])
                                </div>
                            </div>
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
