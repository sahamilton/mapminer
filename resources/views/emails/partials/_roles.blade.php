<fieldset>
	<legend>Roles</legend>
	<ul>
		@foreach($roles as $role)
			<p><input type="checkbox"  name="role[]" value="{{$role->id}}"/> {{$role->name}}</p> 
		@endforeach
	</ul>
</fieldset>
		
