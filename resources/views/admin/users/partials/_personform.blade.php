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
				<!-- ./ lastname -->
		
				
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
		

				<!-- Verticals -->
				<div class="form-group @if ($errors->has('vertical')) has-error @endif">
					{{Form::label('Industry Focus','Industry Focus:', array('class'=>"col-md-2 control-label"))}}

					<div class="col-md-6">
						{{Form::select('vertical[]',$verticals,isset($user) ? $user->person->industryfocus->pluck('id') :'',array('class'=>'form-control','multiple'=>true))}}

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