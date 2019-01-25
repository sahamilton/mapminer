
<!-- id -->
<div class="form-group{{ $errors->has('region') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Region:</label>
	<div class="input-group input-group-lg">
		<input 
			type="text" class="form-control" name='region' 
		description="region" 
		value="{{ old('region', isset($region) ? $region->region :'' ) }}" 
		placeholder="id">
		<span class="help-block">
			<strong>{{ $errors->has('region') ? $errors->first('region') : ''}}</strong>
		</span>
	</div>
</div>

