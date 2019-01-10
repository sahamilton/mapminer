<!-- Email -->
<div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!}">
	<label class="col-md-2 control-label" for="email">Email</label>
	<div class="col-md-10">
		<input
		required 
		class="form-control" 
		type="text" 
		name="email" 
		autocomplete = 'off'
		id="email" 
		value="{{ old('email', isset($user) ? $user->email : '') }}" 
		placeholder="email@peopleready.com"/>
		{!! $errors->first('email', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ email -->
<!-- employee_id -->
<div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Employee Id</label>
    <div class="col-md-10">
        <input 
        required
        type="text" 
        class="form-control" 
        name='employee_id' 
        description="employee_id" 
        value="{{ old('employee_id',isset($user) ? $user->employee_id : '') }}" 
        placeholder="employee_id">
        <span class="help-block{{ $errors->has('employee_id') ? ' has-error' : '' }}">
            <strong>{{ $errors->has('employee_id') ? $errors->first('employee_id') : ''}}</strong>
            </span>
    </div>
</div>
    
<!-- Password -->
<div class="form-group {!! $errors->has('password') ? 'has-error' : '' !!}">
	<label class="col-md-2 control-label" for="password">Password</label>
	<div class="col-md-10">
		<input 
		class="form-control" 
		type="password" 
		name="password" 
		id="password" 
		value="" />
		{!! $errors->first('password', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ password -->

<!-- Password Confirm -->
<div class="form-group {!! $errors->has('password_confirmation') ? 'has-error' : '' !!}">
	<label class="col-md-2 control-label" for="password_confirmation">Password Confirm</label>
	<div class="col-md-10">
		<input 
		class="form-control" 
		type="password" 
		name="password_confirmation" 
		id="password_confirmation" 
		value="" />
		{!! $errors->first('password_confirmation', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ password confirm -->

<!-- confirmed -->
<div class="form-group {!! $errors->has('confirmed') ? 'has-error' : '' !!}">
	<label class="col-md-2 control-label" for="password_confirmation">Active</label>
	<div class="col-md-10">
		<input 
		class="form-control" 
		type="checkbox" 
		name="confirmed" 
		id="confirmed" 
		value="1" {{isset($user) && $user->confirmed==1 ? 'checked' : ''}}/>
		{!! $errors->first('confirmed', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ confirmed -->

<!-- Groups -->
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
            		<option value="{{ $role->id }}"{{ ( in_array($role->id, $selectedRoles) ? ' selected="selected"' : '') }}>{{ $role->name }}</option>
            	@else
					<option value="{{ $role->id }}"{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? ' selected="selected"' : '') }}>{{ $role->name }}</option>
				@endif
            @endforeach
		</select>

		<span class="help-block">
			Select a group to assign to the user, remember that a user takes on the permissions of the group they are assigned.
		</span>
	</div>
</div>
<!-- ./ groups -->

<!-- Service Lines -->
<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
	<label class="col-md-2 control-label" for="roles">Service Lines</label>
	<div class="col-md-6">
		<select 
        required
        class="form-control" 
        name="serviceline[]" 
        id="serviceline" 
        multiple
		oninvalid="this.setCustomValidity('You must choose a serviceline')"
		oninput="this.setCustomValidity('')"  />

			@foreach ($servicelines as $id=>$serviceline)
				
            	<option 
            	value="{{ $id }}"
            	@if(isset($user) &&  in_array($id, $user->currentServicelineIds())) selected @endif
            	>
            	{{ $serviceline }}
            </option>
            	
            @endforeach
		</select>

		@if ($errors->has('serviceline')) <p class="help-block">{!! $errors->first('serviceline') !!}</p> @endif
	</div>
</div>
<!-- ./ servicelines -->

