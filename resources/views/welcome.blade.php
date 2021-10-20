@extends('layouts.base')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-xxl-5 d-flex">
                    <div class="w-100">
                        <div class="row">
                            @foreach($report as $key => $values)
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">{{\App\Core\Services\AnalyticsService::SERVICE_LABELS[$key]}}</h5>
                                                </div>

                                                <div class="col-auto">
                                                    <div class="stat text-primary shadow">{{$values['total']}}</div>
                                                </div>
                                            </div>

                                            <div class="row mb-0">
                                                <div class="col-6">
                                                    <span class="text-success"> <i
                                                            class="mdi mdi-arrow-bottom-right"></i> {{$values['today']}}</span>
                                                    <span class="text-muted">Today</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-danger"> <i
                                                            class="mdi mdi-arrow-bottom-right"></i> {{$values['state']['CSIN']}}</span>
                                                    <span class="text-muted">Pending</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-xxl-7">
                    <div class="card flex-fill w-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Latest movies</h5>
                        </div>
                        <div class="card-body py-3">
                            <table class="table table-sm table-striped my-0">
                                <thead>
                                <tr>
                                    <th class="d-none d-xl-table-cell">Dvd ID</th>
                                    <th class="d-none d-xl-table-cell">Content ID</th>
                                    <th class="d-none d-xl-table-cell">R18</th>
                                    <th class="d-none d-xl-table-cell">Onejav</th>
                                </tr>
                                </thead>
                                <tbody class="text-left">
                                @foreach($movies as $movie)
                                    <tr>
                                        <td><a href="{{route('movie.show', $movie)}}">{{$movie->dvd_id}}</a></td>
                                        <td class="d-none d-xl-table-cell">{{$movie->content_id}}</td>
                                        <td class="d-none d-xl-table-cell">{{$movie->r18 ? 'YES' : 'NO'}}</td>
                                        <td class="d-none d-xl-table-cell">{{$movie->onejav ? 'YES' : 'NO'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
