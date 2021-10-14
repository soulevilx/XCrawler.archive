<div class="card-body">
    <div class="row">
        <div class="col mt-0">
            <h5 class="card-title">{{$title}}</h5>
        </div>

        <div class="col-auto">
            <div class="stat text-primary">
            </div>
        </div>
    </div>
    <h1 class="mt-1 mb-3">{{$total}}</h1>
    <div class="row mb-0">
        <div class="col-6">
            <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> {{$percent}}% </span>
            <span class="text-muted">Today</span>
        </div>
        <div class="col-6">
            <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> {{$init}} </span>
            <span class="text-muted">Pending</span>
        </div>
    </div>
</div>
