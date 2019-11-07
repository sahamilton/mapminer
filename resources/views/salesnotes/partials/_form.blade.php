@foreach($data as $field)

@if($field['group'] != $group)
</div>
    <div id="{{str_replace(" ", "_", $field['group'])}}">
    @php $group = $field['group']; @endphp
@endif

<div class="form-group{{ $errors->has($field['id']) ? ' has-error' : '' }}">
<label class="col-md-4 control-label">{{$field['fieldname']}}</label>

<div class="input-group input-group-lg ">
    
    @if($field['type'] =='text')
    
    <input type="text"
        name="{{$field['id']}}"
        value="{{$field['id']}}"
        />
    @elseif($field['type'] == 'textarea')

    <textarea name="{{$field['id']}}" ></textarea>
    
    
    @elseif ($field['type'] == 'select')

        @include('salesnotes.partials._select')
    
    @elseif( $field['type'] == 'radio')
        @include('salesnotes.partials._radio')
    
    @elseif ( $field['type'] =='checkbox')
        @include('salesnotes.partials._check')
    
    @elseif ($field['type'] == 'multiselect')
        @include('salesnotes.partials._multiselect')

    @elseif ($field['type'] == 'file')
        @include('salesnotes.partials._file')

    @elseif ('attachment')
        @include('salesnotes.partials._attachment')
    

    @else
        <input type="text"
            name="{{$field['id']}}"
            value="{{$field['value']}}"
        />
    @endif 

    <span class="help-block">
                <strong>{{ $errors->has($field['id']) ? $errors->first($field['id']) : ''}}</strong>
                </span>
        </div>
</div>

    
@endforeach    