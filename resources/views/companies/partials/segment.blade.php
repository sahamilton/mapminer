@if(count($segments)>0)
<fieldset>
<label>Segments</label>

@foreach ($segments as $segment)
	
    <a href="{{route('company.segment',[$company->id,$segment->id])}}" >
    <input type='checkbox' name='{{$segment->id}}'  />
    {{$segment->filter}}</a>
    
    


@endforeach
</fieldset>
@endif