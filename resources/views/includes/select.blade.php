<select class="custom-select mr-2" id="inputGroupSelect03" aria-label="Example select with button addon" name="{{$key}}">
    @foreach ($values as $value)
        <option value="{{$value}}" @if (Cookie::get('filter.' . $key) == $value) selected @endif>{{$value}}</option>
    @endforeach
</select>
