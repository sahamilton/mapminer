<!-- companyname -->
<div class="form-group row {{ $errors->has('companyname') ? ' has-error' : '' }}">
   
    <label for="companyname" 
    class="col-sm-2 col-form-label">
        Company Name:   </label>
     <div class="col-sm-8">
        <input 
        required 
        type="text" 
        class="form-control" 
        name='companyname' 
        description="source" 
        value="{{ old('companyname', isset($address) ? $address->businessname : '' )}}" 
        placeholder="companyname">
        <span class="help-block">
            <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
        </span>
    </div>
</div>
<!-- address -->
<div class="form-group row{{ $errors->has('street') ? ' has-error' : '' }}">
    <label for="address" class="col-md-2 control-label">Address: </label>
     <div class="col-sm-8">
        <input required type="text" 
        class="form-control" 
        name='street' 
        description="street" 
        value="{{ old('street', isset($address) ? $address->street : '' )}}" 
        placeholder="street address">
        <span class="help-block">
            <strong>{{ $errors->has('street') ? $errors->first('street') : ''}}</strong>
        </span>
    </div>
</div>
<div class="form-group row{{ $errors->has('street') ? ' has-error' : '' }}">
    <label for="address" class="col-md-2 control-label">Suite/ Unit: </label>
     <div class="col-sm-8">
        <input required type="text" 
        class="form-control" 
        name='address2' 
        description="address2" 
        value="{{ old('address2', isset($address) ? $address->address2 : '' )}}" 
        placeholder="unit / suite">
        <span class="help-block">
            <strong>{{ $errors->has('address2') ? $errors->first('address2') : ''}}</strong>
        </span>
    </div>
</div>
<!-- city -->
<div class="form-group row{{ $errors->has('city') ? ' has-error' : '' }}">
    <label for="city" class="col-md-2 control-label">City: </label>
     <div class="col-sm-8">
        <input required 
        type="text" 
        class="form-control" 
        name='city' 
        description="city" 
        value="{{ old('city', isset($address) ? $address->city : '' )}}" 
        placeholder="city">
        <span class="help-block">
            <strong>{{ $errors->has('city') ? $errors->first('city') : ''}}</strong>
        </span>
    </div>
</div>
<!-- state -->
<div class="form-group row{{ $errors->has('state') ? ' has-error' : '' }}">
    <label for="state" class="col-md-2 control-label">State: </label>
     <div class="col-sm-8">
        <input required type="text" 
        class="form-control" 
        name='state' 
        description="state" 
        value="{{ old('state', isset($address) ? $address->state : '' )}}" 
        placeholder="state, city state zip">
        <span class="help-block">
            <strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
        </span>
    </div>
</div>
<!-- zip -->
<div class="form-group row{{ $errors->has('zip') ? ' has-error' : '' }}">
    <label for="zip" class="col-md-2 control-label">zip: </label>
     <div class="col-sm-8">
        <input required type="text" 
        class="form-control" 
        name='zip' 
        description="zip" 
        value="{{ old('zip', isset($address) ? $address->zip : '' )}}" 
        placeholder="zip, city state zip">
        <span class="help-block">
            <strong>{{ $errors->has('zip') ? $errors->first('zip') : ''}}</strong>
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
        value="{{ old('phone', isset($address) ? $address->phone : '' )}}" 
        placeholder="phone">
        <span class="help-block">
            <strong>{{ $errors->has('phone') ? $errors->first('phone') : ''}}</strong>
        </span>
    </div>
</div>
<div class="form-group row{{ $errors->has('customer_id') ? ' has-error' : '' }}">
    <label for="customer_id" class="col-md-2 control-label">Customer Id: </label>
     <div class="col-sm-8">
        <input 
        type="text" 
        class="form-control" 
        name='customer_id' 
        description="Customer id" 
        value="{{ old('customer_id', isset($address) ? $address->customer_id : '' )}}" 
        placeholder="Customer Id">
        <span class="help-block">
            <strong>{{ $errors->has('customer_id') ? $errors->first('customer_id') : ''}}</strong>
        </span>
    </div>
</div>

