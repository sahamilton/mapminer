@if(count($segments)>0)
<fieldset>
<label>Segments</label>

@foreach ($segments as $segment)
	@if(isset($data['segment']) && $data['segment'] == $segment->filter)
        <input type='checkbox' name ='{{$segment->id}}'checked  />
        {{$segment->filter}}
    @else
    <a href="/company/{{$data['company']}}/segment/{{$segment->id}}" >
    <input type='checkbox' name='{{$segment->id}}'  />
    {{$segment->filter}}</a>
    
    @endif


@endforeach
</fieldset>
@endif