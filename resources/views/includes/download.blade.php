<form action="{{$route}}" method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">{{$title}}</label>
        <input type="text" name="url" class="form-control" id="url" aria-describedby="urlHelp">
        <small id="emailHelp" class="form-text text-muted">{{$description}}</small>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Submit</button>
</form>
