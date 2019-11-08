@php
    $options = explode(",", $field['values']);
    $selected = explode(",", $field['value']);
    $fieldname = $field['id']."[]";
@endphp

<select multiple
    name="{{$field['id']}}[]"
    >
    @foreach ($options as $option)
    <option value="{{$option}}"
        @if(in_array($option, $selected))
            selected
        @endif 
        >{{$option}}</option>
    @endforeach
</select>
