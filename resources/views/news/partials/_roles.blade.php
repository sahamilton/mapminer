<div class="form-group {!! $errors->has('roles') ? 'has-error' : '' !!}">

    <label class="col-md-2 control-label" for="roles">Roles</label>
    <div class="col-md-6">
        <select 
        required
        class="form-control" 
        name="roles[]" 
        id="roles" 
        multiple
		oninvalid="this.setCustomValidity('You must choose a role')"
		oninput="this.setCustomValidity('')"  />
			@foreach ($roles as $role)
				
            		<option value="{{ $role->id }}"
                        @if(isset($news) && in_array($role->id, $news->relatedRoles->pluck('id')->toArray()))
                        selected 
                        @endif
                        >
                        {{ $role->display_name }}</option>
            @endforeach
		</select>

		
	</div>
</div>


