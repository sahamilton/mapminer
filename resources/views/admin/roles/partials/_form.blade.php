{{-- Create Role Form --}}

		<!-- Name -->
				<div class="form-group {{{ $errors->has('display_name') ? 'has-error' : '' }}}">
					<label class="col-md-2 control-label" for="name">Name</label>
                    <div class="col-md-10">
    					<input required class="form-control" type="text" name="display_name" id="display_name" 
                        value="{{old('display_name', isset($role) ?  $role->display_name : '')}}" />
    					{!! $errors->first('display_name', '<span class="help-inline has-error">:message</span>') !!}
                    </div>
				</div>
				<!-- ./ name -->
			<hr />

	        <!-- Permissions tab -->
	  
                
                    @foreach ($permissions as $permission)
                    <div class="form-group">
                    <label class="col-md-2 control-label" for="{{$permission['display_name']}}">
                         {{{ ucwords($permission['display_name']) }}}</label>
                    <input class="col-md-2" type="checkbox" name="permissions[]" value="{{ $permission['id'] }}"  
                    {!! isset($currentPermissions) && in_array($permission['id'],$currentPermissions) ? 'checked' :'' !!}

                    />
                        
                    </div>
                    @endforeach
                
	        
	  

		<!-- ./ tabs content -->