<option
    value="{{$value}}"
    {{ app('request')->input($name) == $value || (isset($default) && $default) ? 'selected'  :  ''}}
>{{$text}}</option>
