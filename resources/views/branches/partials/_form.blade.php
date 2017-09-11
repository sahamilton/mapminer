<?php

$state = new App\State;
$states = $state->getStates();

?>

<!-- branchnumber -->
<div class="form-group{{ $errors->has('branchnumber') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Branch Number:</label>
	<div class="input-group input-group-lg">
		<input type="text" class="form-control" name='branchnumber' description="branchnumber" value="{{ old('branchnumber') ? old('branchnumber') : isset($data->branchnumber) ? $data->branchnumber : "" }}" placeholder="branchnumber">
		<span class="help-block">
			<strong>{{ $errors->has('branchnumber') ? $errors->first('branchnumber') : ''}}</strong>
		</span>
	</div>
</div>


<!-- branchname -->
<div class="form-group{{ $errors->has('branchname') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Branch Name</label>
	<div class="input-group input-group-lg">
		<input type="text" class="form-control" name='branchname' description="branchname" value="{{ old('branchname') ? old('branchname') : isset($data->branchname) ? $data->branchname : "" }}" placeholder="branchname">
		<span class="help-block">
			<strong>{{ $errors->has('branchname') ? $errors->first('branchname') : ''}}</strong>
		</span>
	</div>
</div>

<!-- street -->
<div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Address:</label>
	<div class="input-group input-group-lg">
		<input type="text" class="form-control" name='street' description="street" value="{{ old('street') ? old('street') : isset($data->street) ? $data->street : "" }}" placeholder="street">
		<span class="help-block">
			<strong>{{ $errors->has('street') ? $errors->first('street') : ''}}</strong>
		</span>
	</div>
</div>





<!-- address2 -->
<div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Suite/Unit:</label>
	<div class="input-group input-group-lg">
		<input type="text" class="form-control" name='address2' description="address2" value="{{ old('address2') ? old('address2') : isset($data->address2) ? $data->address2 : "" }}" placeholder="address2">
		<span class="help-block">
			<strong>{{ $errors->has('address2') ? $errors->first('address2') : ''}}</strong>
		</span>
	</div>
</div>



<!-- city -->
<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">City:</label>
	<div class="input-group input-group-lg">
		<input type="text" class="form-control" name='city' description="city" value="{{ old('city') ? old('city') : isset($data->city) ? $data->city : "" }}" placeholder="city">
		<span class="help-block">
			<strong>{{ $errors->has('city') ? $errors->first('city') : ''}}</strong>
		</span>
	</div>
</div>


<div class="form-group{{ $errors->has('state)') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">States:</label>
	<div class="input-group input-group-lg">
		<select  class="form-control" name='state'>
		@foreach ($states as $state))
			<option value="{{$state}}">{{$state}}</option>
		@endforeach
		</select>
		<span class="help-block">
			<strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
		</span>
	</div>
</div>

<!-- zip -->
<div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">ZIP / Postal Code:</label>
	<div class="input-group input-group-lg">
		<input type="text" class="form-control" name='zip' description="zip" value="{{ old('zip') ? old('zip') : isset($data->zip) ? $data->zip : "" }}" placeholder="zip">
		<span class="help-block">
			<strong>{{ $errors->has('zip') ? $errors->first('zip') : ''}}</strong>
		</span>
	</div>
</div>




<!-- radius -->
<div class="form-group{{ $errors->has('radius') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Service Radius in miles:</label>
	<div class="input-group input-group-lg">
		<input type="text" class="form-control" name='radius' description="radius" value="{{ old('radius') ? old('radius') : isset($data->radius) ? $data->radius : "25" }}" placeholder="service radius">
		<span class="help-block">
			<strong>{{ $errors->has('radius') ? $errors->first('radius') : ''}}</strong>
		</span>
	</div>
</div>

<div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Servicelines</label>
	<div class="input-group input-group-lg">
		<select multiple class="form-control" name='serviceline[]'>
			@foreach ($servicelines as $serviceline))
				<option value="{{$serviceline->id}}">{{$serviceline->ServiceLine}}</option>
			@endforeach
		</select>
		<span class="help-block">
			<strong>{{ $errors->has('serviceline') ? $errors->first('serviceline') : ''}}</strong>
		</span>
	</div>
</div>

<?php $regions = [ '1'=>'Western' ,'2'=>'CLP','3'=>'Eastern','4'=>'Mid-America & Canada
'];?>
<div class="form-group{{ $errors->has('region)') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Region:</label>
	<div class="input-group input-group-lg">
		<select  class="form-control" name='region'>
			@foreach ($regions as $region))
				<option value="{{$region}}">{{$region}}</option>
			@endforeach
		</select>
		<span class="help-block">
			<strong>{{ $errors->has('region') ? $errors->first('region') : ''}}</strong>
		</span>
	</div>
</div>








