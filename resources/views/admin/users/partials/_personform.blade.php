			<!-- firstname -->
				<div class="form-group {!! $errors->has('firstname') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="firstname">First Name</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="firstname" id="firstname" value="{!! Input::old('firstname', isset($user->person->firstname) ? $user->person->firstname : null) !!}" 
						placeholder="first name"/>
						{!! $errors->first('firstname', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ firstname -->
                
                <!-- lastname -->
				<div class="form-group {!! $errors->has('lastname') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="lastname">Last Name</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="lastname" id="lastname" value="{!!Input::old('lastname', isset($user->person->lastname) ? $user->person->lastname : null) !!}" 
						placeholder="last name"/>
						{!! $errors->first('lastname', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ lastname -->
		
				
				<!-- Address -->
				<div class="form-group {!! $errors->has('address') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="address">Full Address</label>
					<div class="col-md-6">
						<input class="form-control" type="text" 
						placeholder="Full address with city & state"
						name="address" id="address" value="{!!Input::old('address', isset($user) ? $user->person->address : null) !!}" 
						/>
						{!! $errors->first('address', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ address -->
				@if(isset($user->person->city))
				<!-- City -->
				<div class="form-group {!! $errors->has('city') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="city">City</label>
					<div class="col-md-6">
						<input class="form-control" type="text" 
						placeholder="Leave blank unless you want to override geocode"
						name="city" id="city" value="{!!Input::old('city', isset($user) ? $user->person->city : null) !!}" />
						{!! $errors->first('city', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				@endif
				<!-- ./ city -->
				@if(isset($user->person->state))
				<!-- State -->
				<div class="form-group {!! $errors->has('state') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="state">State</label>
					<div class="col-md-6">
						<input class="form-control" type="text" 
						placeholder="Leave blank unless you want to override geocode"
						name="state" id="state" value="{!!Input::old('state', isset($user) ? $user->person->state : null) !!}" />
						{!! $errors->first('state', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ state -->
				@endif

				<!-- Phone -->
				<div class="form-group {!! $errors->has('phone') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="address">Phone</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="phone" id="phone"  value="{!!Input::old('phone', isset($user) ? $user->person->phone : null) !!}" 
						placeholder="phone"/>
						{!! $errors->first('phone', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ phone -->
		

				<!-- Verticals -->
				<div class="form-group @if ($errors->has('vertical')) has-error @endif">
					{{Form::label('Industry Focus','Industry Focus:', array('class'=>"col-md-2 control-label"))}}

					<div class="col-md-6">
						{{Form::select('vertical[]',$verticals,isset($user) ? $user->person->industryfocus->pluck('id')->toArray() :'',array('class'=>'form-control','multiple'=>true))}}

						@if ($errors->has('vertical')) <p class="help-block">{!! $errors->first('vertical') !!}</p> @endif
					</div>
				</div>
				<!-- ./ verticals -->
			<!--- Managers ---->
			<div class="form-group{{ $errors->has('reports_to)') ? ' has-error' : '' }}">
                        <label class="col-md-2 control-label">Managers</label>
                        <div class="col-md-6">
                            <select class="form-control" name='reports_to'>
                            @if(! isset($user->person->reports_to))
                				<option value=''>N/A</option>
                			@else
								<option selected value=''>N/A</option>
                			@endif
                				
                            @foreach ($managers as $key=>$value))
	                            @if(isset($user->person->reports_to) && $user->person->reports_to == $key)
	                            	<option selected value="{{$key}}">
	                            	{{$value}}
	                            	</option>
	                			@else
									<option value="{{$key}}">
									{{$value}}
									</option>
	                			@endif
                            @endforeach
                
                
                            </select>
                            <span class="help-block{{ $errors->has('reports_to)') ? ' has-error' : '' }}">
                                <strong>{{ $errors->has('manager') ? $errors->first('manager') : ''}}</strong>
                                </span>
                        </div>
                    </div>
                   
            <!---./ Managers ---->
            
            <!--- Branches ---->


<div class="form-group {!! $errors->has('branches') ? 'has-error' : '' !!}">
	<label class="col-md-2 control-label" for="roles">Branch Association</label>

	<div class="col-md-6">
		<select multiple class="form-control" name='branches[]'>
	
			@foreach ($branches as $key=>$value))
				@if(isset($branchesServiced) && in_array($key,$branchesServiced))
					<option selected value="{{$key}}">{{$value}}</option>
				@else
					<option value="{{$key}}">{{$value}}</option>
				@endif
			@endforeach
		</select>
		<span class="help-block">
			<strong>
				{{ $errors->has('branches') ? $errors->first('branches') : ''}}
			</strong>
		</span>

	</div>
</div>



				<!---- or enter comma separated string -->  
				<div class="form-group {!! $errors->has('branchstring') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="branchstring">Branches</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="branchstring" id="branchstring"  />
						{!! $errors->first('branchstring', '<span class="help-inline">:message</span>') !!}
					
					<span class="help-block">
							or enter a comma separated list of branches.
						</span></div>
				</div>

            <!---./ branches ---->