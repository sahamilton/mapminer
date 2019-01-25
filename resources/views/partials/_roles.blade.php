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
				@if ($mode == 'create')
            		<option value="{{ $role->id }}"{{ ( in_array($role->id, $selectedRoles) ? ' selected="selected"' : '') }}>{{ $role->display_name }}</option>
            	@else
					<option value="{{ $role->id }}"{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? ' selected="selected"' : '') }}>{{ $role->display_name }}</option>
				@endif
            @endforeach
		</select>

		
	</div>
</div>