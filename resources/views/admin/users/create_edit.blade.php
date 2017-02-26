@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
	<div style = "margin-top:30px;">
    
    </div>

	{{-- Create User Form --}}
	<form class="form-horizontal" method="post" action="@if (isset($user)){{ URL::to('admin/users/' . $user->id . '/edit') }}@endif" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->
<!-- firstname -->
				<div class="form-group {{{ $errors->has('firstname') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="firstname">First Name</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="firstname" id="firstname" value="{{{ Input::old('firstname', isset($user->person->firstname) ? $user->person->firstname : null) }}}" />
						{{ $errors->first('firstname', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ firstname -->
                
                <!-- lastname -->
				<div class="form-group {{{ $errors->has('lastname') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="lastname">Last Name</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="lastname" id="lastname" value="{{{ Input::old('lastname', isset($user->person->lastname) ? $user->person->lastname : null) }}}" />
						{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ username -->
		
				<!-- username -->
				<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="username">Username</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="username" id="username" value="{{{ Input::old('username', isset($user) ? $user->username : null) }}}" />
						{{ $errors->first('username', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ username -->

				<!-- Email -->
				<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="email">Email</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email', isset($user) ? $user->email : null) }}}" />
						{{ $errors->first('email', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ email -->

				<!-- Password -->
				<div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password">Password</label>
					<div class="col-md-10">
						<input class="form-control" type="password" name="password" id="password" value="" />
						{{ $errors->first('password', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ password -->

				<!-- Password Confirm -->
				<div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password_confirmation">Password Confirm</label>
					<div class="col-md-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
						{{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ password confirm -->
				<!-- Address -->
				<div class="form-group {{{ $errors->has('address') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="address">Full Address</label>
					<div class="col-md-10">
						<input class="form-control" type="text" 
						placeholder="Full address with city & state"
						name="address" id="address" value="{{{ Input::old('address', isset($user) ? $user->person->address : null) }}}" />
						{{ $errors->first('address', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ address -->
				@if(isset($user->person->city))
				<!-- City -->
				<div class="form-group {{{ $errors->has('city') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="city">City</label>
					<div class="col-md-10">
						<input class="form-control" type="text" 
						placeholder="Leave blank unless you want to override geocode"
						name="city" id="city" value="{{{ Input::old('city', isset($user) ? $user->person->city : null) }}}" />
						{{ $errors->first('city', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				@endif
				<!-- ./ city -->
				@if(isset($user->person->state))
				<!-- State -->
				<div class="form-group {{{ $errors->has('state') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="state">State</label>
					<div class="col-md-10">
						<input class="form-control" type="text" 
						placeholder="Leave blank unless you want to override geocode"
						name="state" id="state" value="{{{ Input::old('state', isset($user) ? $user->person->state : null) }}}" />
						{{ $errors->first('state', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ state -->
				@endif

				<!-- Phone -->
				<div class="form-group {{{ $errors->has('phone') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="address">Phone</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="phone" id="phone"  value="{{{ Input::old('phone', isset($user) ? $user->person->phone : null) }}}" />
						{{ $errors->first('phone', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ phone -->
				<!-- Activation Status -->
				<div class="form-group {{{ $errors->has('activated') || $errors->has('confirm') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="confirm">Activate User?</label>
					<div class="col-md-6">
						@if ($mode == 'create')
							<select class="form-control" name="confirm" id="confirm">
								<option value="1"{{{ (Input::old('confirm', 0) === 1 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
								<option value="0"{{{ (Input::old('confirm', 0) === 0 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
							</select>
						@else
							<select class="form-control" {{{ ($user->id === Confide::user()->id ? ' disabled="disabled"' : '') }}} name="confirm" id="confirm">
								<option value="1"{{{ ($user->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
								<option value="0"{{{ ( ! $user->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
							</select>
						@endif
						{{ $errors->first('confirm', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				<!-- ./ activation status -->

				<!-- Groups -->
				<div class="form-group {{{ $errors->has('roles') ? 'error' : '' }}}">
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
						{{Form::select('serviceline[]',$servicelines,isset($user) ? $user->serviceline->lists('id') :'',array('class'=>'form-control','multiple'=>true))}}

						@if ($errors->has('serviceline')) <p class="help-block">{{ $errors->first('serviceline') }}</p> @endif
					</div>
				</div>
				<!-- ./ servicelines -->

				<!-- Verticals -->
				<div class="form-group @if ($errors->has('vertical')) has-error @endif">
					{{Form::label('Industry Focus','Industry Focus:', array('class'=>"col-md-2 control-label"))}}

					<div class="col-md-6">
						{{Form::select('vertical[]',$verticals,isset($user) ? $user->person->industryfocus->lists('id') :'',array('class'=>'form-control','multiple'=>true))}}

						@if ($errors->has('vertical')) <p class="help-block">{{ $errors->first('vertical') }}</p> @endif
					</div>
				</div>
				<!-- ./ verticals -->
                
                
			<!--- Managers ---->
            <div class="form-group {{{ $errors->has('manager') ? 'error' : '' }}}">
	                <label class="col-md-2 control-label" for="roles">Manager</label>
	                <div class="col-md-6">
		                {{Form::select('mgrid',$managerlist,isset($user->person) ?$user->person->reports_to : '' ,array('class'=>"form-control"))}}
                     <span class="help-block">
							Select the manager the user reports to.
						</span>
	            	</div>
				</div>   
            <!---./ Managers ---->
            
            <!--- Branches ---->
            <div class="form-group {{{ $errors->has('branches') ? 'error' : '' }}}">
	                <label class="col-md-2 control-label" for="roles">Branch Association</label>
	                <div class="col-md-6">
		                {{Form::select('branches[]',$branches, isset($branchesServiced) ? $branchesServiced : '' ,array('class'=>"form-control",'multiple'=>true))}}
                     <span class="help-block">
							Select the branches associated with this user.
						</span>
	            	</div>
				</div> 
				<!---- or entr comma separated string -->  
				<div class="form-group {{{ $errors->has('branchstring') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="branchstring">Branches</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="branchstring" id="branchstring"  />
						{{ $errors->first('branchstring', '<span class="help-inline">:message</span>') }}
					
					<span class="help-block">
							or enter a comma separated list of branches.
						</span></div>
				</div>

            <!---./ branches ---->
		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				
				<button type="submit" class="btn btn-success">OK</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop
