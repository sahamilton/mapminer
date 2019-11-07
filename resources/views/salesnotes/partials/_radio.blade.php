@php
/**
 * Generate Radio Button
 * 
 * 
 * 
 */
$options = explode(",", $field['values']) 
$selected = explode(",", $field['value']);
@endphp
@if (is_array($options)) 
    @foreach ($options as $value) 
        {{$value." "}}
        
            <input type='radio' 
                name="{{$field['id']}}"
                 @if (in_array($value, $selected))
                 checked 
                 @endif
                value="{{$value}}" />
        
    @endforeach
@endif