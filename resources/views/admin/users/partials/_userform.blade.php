
				<!-- username -->
				<div class="form-group {!! $errors->has('username') ? 'has-error' : ''!!}">
					<label class="col-md-2 control-label" for="username">Username</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="username" id="username" value="{{{ Input::old('username', isset($user) ? $user->username : null) }}}" 
						placeholder="user name"/>
						{!! $errors->first('username', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ username -->

				<!-- Email -->
				<div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="email">Email</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email', isset($user) ? $user->email : null) }}}" 
						placeholder="email@peopleready.com"/>
						{!! $errors->first('email', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ email -->
				<!-- employee_id -->
				    <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
				        <label class="col-md-2 control-label">Employee Id</label>
				            <div class="col-md-10">
				                <input type="text" class="form-control" name='employee_id' description="employee_id" value="{{ old('employee_id') ? old('employee_id') : isset($data->employee_id) ? $data->employee_id : "" }}" placeholder="employee_id">
				                <span class="help-block{{ $errors->has('employee_id') ? ' has-error' : '' }}">
				                    <strong>{{ $errors->has('employee_id') ? $errors->first('employee_id') : ''}}</strong>
				                    </span>
				            </div>
				    </div>
				    
				<!-- Password -->
				<div class="form-group {!! $errors->has('password') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="password">Password</label>
					<div class="col-md-10">
						<input class="form-control" type="password" name="password" id="password" value="" />
						{!! $errors->first('password', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ password -->

				<!-- Password Confirm -->
				<div class="form-group {!! $errors->has('password_confirmation') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="password_confirmation">Password Confirm</label>
					<div class="col-md-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
						{!! $errors->first('password_confirmation', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ password confirm -->

				<!-- confirmed -->
				<div class="form-group {!! $errors->has('confirmed') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="password_confirmation">Active</label>
					<div class="col-md-10">
						<input class="form-control" type="checkbox" name="confirmed" id="confirmed" value="1" {{isset($user->confirmed) ? 'checked' : ''}}/>
						{!! $errors->first('confirmed', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ confirmed -->
				
				<!-- Groups -->
				<div class="form-group {!! $errors->has('roles') ? 'has-error' : '' !!}">
	                <label class="col-md-2 control-label" for="roles">Roles</label>
	                <div class="col-md-6">
		                <select class="form-control" name="roles[]" id="roles[]" multiple>
		                        @foreach ($roles as $role)
									@if ($mode == 'create')
		                        		<option value="{{{ $role->id }}}"{{{ ( in_array($role->id, $selectedRoles) ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
		                        	@else
										<option value="{{{ $role->id }}}"{{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
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
					{{Form::label('ServiceLine','Service Lines:', array('class'=>"col-md-2 control-label"))}}

					<div class="col-md-6">
						{{Form::select('serviceline[]',$servicelines,isset($user) ? $user->serviceline->pluck('id')->toArray() :'',array('class'=>'form-control','multiple'=>true))}}

						@if ($errors->has('serviceline')) <p class="help-block">{!! $errors->first('serviceline') !!}</p> @endif
					</div>
				</div>
				<!-- ./ servicelines -->

				