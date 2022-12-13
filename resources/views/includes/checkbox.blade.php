@foreach ($values as $value)
    <input type="checkbox" id="" name="{{$key}}" value="{{$value}}" class="mr-2"
           @if (Cookie::get('filter.' . $key) == $value) checked @endif>
    <label for="" class="mr-2"> {{$value}}</label><br>
@endforeach

