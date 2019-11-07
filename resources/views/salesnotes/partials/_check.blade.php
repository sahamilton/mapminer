/**
 * generate Checkbox option
 * @type {[type]}
 */
@php $options = explode(",", $field['values']); @endphp

@if (is_array($options))
    @foreach ($options as $value)
     {{$value." "}}
        <input type="checkbox"
                name="{{$field['id']}}[]"
                value= "{{$value}}"
                @if (is_array($field['value']) && in_array($value, $field['value']))
                    checked
                @endif
        />
    @endforeach
@endif
            