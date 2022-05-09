<!-- firstname -->
<div class="form-group {!! $errors->has('firstname') ? 'has-error' : '' !!}">
	<label class="col-md-3 control-label" for="firstname">First Name</label>
	<div class="input-group input-group-lg">
		<input 
		required
		class="form-control" 
		type="text" 
		name="firstname" 
		id="firstname" 
		value="{{old('firstname', isset($user) && isset($user->person)  ? $user->person->firstname : '') }}" 
		placeholder="first name"/>
		{!! $errors->first('firstname', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ firstname -->

<!-- lastname -->
<div class="form-group {!! $errors->has('lastname') ? 'has-error' : '' !!}">
	<label class="col-md-3 control-label" for="lastname">Last Name</label>
	<div class="input-group input-group-lg">
		<input 
		required
		class="form-control" 
		type="text" 
		name="lastname" 
		id="lastname" 
		value="{{ old('lastname', isset($user) && isset($user->person) ? $user->person->lastname : '') }}" 
		placeholder="last name"/>
		{!! $errors->first('lastname', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ lastname -->


<!-- Address -->
<div class="form-group {!! $errors->has('address') ? 'has-error' : '' !!}">
	<label class="col-md-3 control-label" for="address">Full Address</label>
	<div class="input-group input-group-lg">
		<input 
		class="form-control" 
		type="text" 
		required
		placeholder="Full address with city & state"
		name="address" 
		id="address" 
		value="{{old('address', isset($user) && isset($user->person) ? $user->person->fullAddress() : '') }}" 
		/>
		{!! $errors->first('address', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ address -->
@if(isset($user->person->city))
<!-- City -->
<div class="form-group {!! $errors->has('city') ? 'has-error' : '' !!}">
	<label class="col-md-3 control-label" for="city">City</label>
	<div class="input-group input-group-lg">
		<input 
		class="form-control" 
		type="text" 
		placeholder="Leave blank unless you want to override geocode"
		name="city" 
		id="city" 
		value="{{old('city', isset($user) && isset($user->person) ? $user->person->city : '') }}" />
		{!! $errors->first('city', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
@endif
<!-- ./ city -->
@if(isset($user->person->state))
<!-- State -->
<div class="form-group {!! $errors->has('state') ? 'has-error' : '' !!}">
	<label class="col-md-3 control-label" for="state">State</label>
	<div class="input-group input-group-lg">
		<input 
		class="form-control" 
		type="text" 
		placeholder="Leave blank unless you want to override geocode"
		name="state" 
		id="state" 
		value="{{old('state', isset($user) ? $user->person->state : null) }}" />
		{!! $errors->first('state', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- ./ state -->
@endif

<!-- Phone -->
<div class="form-group {!! $errors->has('phone') ? 'has-error' : '' !!}">
	<label class="col-md-3 control-label" for="address">Phone</label>
	<div class="input-group input-group-lg">
		<input class="form-control" 
		type="text" 
		name="phone" 
		id="phone"  
		value="{{old('phone', isset($user) && isset($user->person) ? $user->person->phone : '') }}" 
		placeholder="phone"/>
		{!! $errors->first('phone', '<span class="help-inline">:message</span>') !!}
	</div>
</div>
<!-- Business Title -->
<div class="form-group {!! $errors->has('business_title') ? 'has-error' : '' !!}">
	<label class="col-md-3 control-label" for="address">Business Title</label>
	<div class="input-group input-group-lg">
		<input class="form-control" 
		required
		type="text" 
		name="business_title" 
		id="business_title"  
		value="{{old('business_title', isset($user) && isset($user->person) ? $user->person->business_title : '') }}" 
		placeholder="business title"/>
		{!! $errors->first('business_title', '<span class="help-inline">:message</span>') !!}
	</div>
</div>

<!--- Managers ---->
<div class="form-group{{ $errors->has('reports_to)') ? ' has-error' : '' }}">
        <label class="col-md-3 control-label">Reports To</label>
        <div class="col-md-6">
            <select class="form-control" name='reports_to'>
            @if(! isset($user->person->reports_to))
				<option value=''>N/A</option>
			@else
				<option selected value=''>N/A</option>
			@endif
			
            @foreach ($managers as $key=>$value))
            <option 
                @if(isset($user->person->reports_to) && $user->person->reports_to == $key)
                	selected 
                @endif
                	value="{{$key}}">
                	{{$value}}
                	</option>
    			
            @endforeach


            </select>
            <span class="help-block{{ $errors->has('reports_to)') ? ' has-error' : '' }}">
                <strong>{{ $errors->has('manager') ? $errors->first('manager') : ''}}</strong>
                </span>
        </div>
    </div>
   
<!---./ Managers ---->
            
            <!--- Branches ---->
<fieldset><legend>Branches</fieldset>
<span class="help-block">
		Select from the list of branches
	</span>
<div class="form-group {!! $errors->has('branches') ? 'has-error' : '' !!}">
	
    <label class="col-md-2 control-label" for="roles">Branches Managed:</label>
    <div class="col-md-6">
        <select 

        class="form-control" 
        name="branches[]" 
        id="branches" 
        multiple
	
			@foreach ($branches as $key=>$value))
				@if(isset($branchesServiced) && in_array($key,array_keys($branchesServiced)))
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


<span class="help-block">
		or enter a comma separated list of branches.
	</span>
<!---- or enter comma separated string -->  
<div class="form-group {!! $errors->has('branchstring') ? 'has-error' : '' !!}">

<div class="input-group input-group-lg">
	<input class="form-control" type="text" name="branchstring" id="branchstring"  />
	{!! $errors->first('branchstring', '<span class="help-inline">:message</span>') !!}

</div>
</div>

            <!---./ branches ---->