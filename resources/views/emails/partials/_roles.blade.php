<fieldset>
	<legend>Roles</legend>
	<ul>
		@foreach($roles as $role)
			<p><input type="checkbox"  name="role[]" value="{{$role->id}}"/> {{$role->display_name}}</p> 
		@endforeach
	</ul>
</fieldset>
		
