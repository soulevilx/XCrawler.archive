@section('footer')
    <footer class="footer">
        <div class="container-fluid">
            <div class="row text-muted">
                <div class="col-6 text-start">
                    <p class="mb-0">
                        <a class="text-muted" href="https://soulevil.com" target="_blank" rel="noopener"><strong>SoulEvil</strong></a>
                        &copy;
                    </p>
                </div>
                <div class="col-6 text-end">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a class="text-muted" href="{{$facebook ?? 'https://facebook.com/SoulEvilX/'}}" target="_blank" rel="noopener"><i class="fab fa-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted" href="{{$twitter ?? 'https://twitter.com/SoulEvil'}}" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
@endsection
