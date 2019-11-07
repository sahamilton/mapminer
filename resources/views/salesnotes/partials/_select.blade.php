@php 
    $options = explode(",", $field['values']); 
@endphp
<select name="{{$field['id']}}">
    @foreach ($options as $option)
    <option value="{{$option}}"
    @if($field['value'] == $option)
        selected
    @endif
    >{{$option}}</option>
    @endforeach
</select>