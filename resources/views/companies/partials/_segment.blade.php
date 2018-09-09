@if($segments->count()>1)
<fieldset>
	<label>Segments</label>

		@foreach ($segments as $key=>$segment)

		    <a href="{{route('company.segment',[$company->id,$key])}}" >
		    <input type='radio' @if(isset($data['segment']) && $data['segment']==$segment) checked  @endif name='{{$key}}'  />
		    {{$segment}}
		    </a>
		    
		@endforeach
</fieldset>
	@if(isset($data['segment']) && $data['segment']!='All')	
		<p><a href="{{route('company.show',$company->id)}}">See all {{$company->companyname}} locations</a>
	@endif
@endif
