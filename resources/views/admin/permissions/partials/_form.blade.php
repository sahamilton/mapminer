<div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="name">Name</label>
                    <div class="col-md-10">
    					<input required class="form-control" type="text" name="name" id="name" value="{{ old('name',isset($permission) ? $permission->display_name : '')}}" />
    					{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
                    </div>
				</div>
				<!-- ./ name -->
		
			<!-- ./ tab general -->
			<fieldset><legend>Assigned to Roles</legend>
	        <!-- Permissions tab -->
	         @foreach ($roles as $role)
                <div class="form-group">
                   
                                        
                    <label class="col-md-2 control-label" for="roles[{{{$role['name']}}}]">{{{ $role['name'] }}}</label>
                    <div class="col-md-10">

                        <input class="form-control" type="checkbox" id="roles[{{{ $role['id'] }}}]" name="roles[]" value="{{{ $role['id'] }}}" {{ (in_array($role['id'], $currentRoles) ? ' checked="checked"' : '')}} />
                        
                        </div>

                    
                </div>
@endforeach
</fieldset>