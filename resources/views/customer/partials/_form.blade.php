<!-- companyname -->
<div class="form-group row {{ $errors->has('companyname') ? ' has-error' : '' }}">
    <label for="companyname" 
    class="col-sm-2 col-form-label">
		Company Name: 
	</label>
     <div class="col-sm-8">
        <input 
        required 
        type="text" 
        class="form-control" 
        name='companyname' 
        description="source" 
        value="{{ old('companyname', isset($mylead) ? $location->companyname : '' )}}" 
        placeholder="companyname">
        <span class="help-block">
            <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
        </span>
    </div>
</div>
<!-- address -->
<div class="form-group row{{ $errors->has('address') ? ' has-error' : '' }}">
    <label for="address" class="col-md-2 control-label">Address: </label>
     <div class="col-sm-8">
        <input required type="text" 
        class="form-control" 
        name='address' 
        description="address" 
        value="{{ old('address', isset($mylead) ? $location->fullAddress() : '' )}}" 
        placeholder="address, city state zip">
        <span class="help-block">
            <strong>{{ $errors->has('address') ? $errors->first('address') : ''}}</strong>
        </span>
    </div>
</div>
<!-- phone -->
<div class="form-group row{{ $errors->has('phone') ? ' has-error' : '' }}">
    <label for="phone" class="col-md-2 control-label">Phone: </label>
     <div class="col-sm-8">
        <input 
        type="text" 
        class="form-control" 
        name='phone' 
        description="phone" 
        value="{{ old('phone', isset($mylead) ? $location->phone : '' )}}" 
        placeholder="phone">
        <span class="help-block">
            <strong>{{ $errors->has('phone') ? $errors->first('phone') : ''}}</strong>
        </span>
    </div>
</div>