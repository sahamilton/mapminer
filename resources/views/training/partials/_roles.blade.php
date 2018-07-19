
@foreach ($roles as $key=>$value)
	<p>
	@if((isset($training->relatedRoles) && $training->relatedRoles->contains('id',$key))
	or is_array(old('role')) && in_array($key,old('role')))
	<input type="checkbox" name="role[]" checked value="{{$key}}">{{$value}}
	@else
	<input type="checkbox" name="role[]"  value="{{$key}}">{{$value}}
	@endif
</p>
@endforeach