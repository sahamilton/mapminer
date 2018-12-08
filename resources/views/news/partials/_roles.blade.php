<?php $roles = \App\Role::pluck('name','id')->toArray();?>

<li><input type="checkbox" name="role[]" id="checkAll" value="">Check All Roles
@foreach ($roles as $key=>$value)
	<p>
	@if((isset($news->relatedRoles) && $news->relatedRoles->contains('id',$key))
	or is_array(old('role')) && in_array($key,old('role')))
	<input type="checkbox" name="role[]" checked value="{{$key}}">{{$value}}
	@else
	<input type="checkbox" name="role[]"  value="{{$key}}">{{$value}}
	@endif
</p>
@endforeach
</li>
