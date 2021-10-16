<option value="{{$value}}" {{ app('request')->input($name) == $value ? 'selected'  : ''}}>{{$text}}</option>
