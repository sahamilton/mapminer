<!-- companyname -->
<div class="form-group row {{ $errors->has('companyname') ? ' has-error' : '' }}">
    <label for="companyname" 
    class="col-sm-2 col-form-label">
		Company Name: 	</label>
     <div class="col-sm-8">
        <input 
        required 
        type="text" 
        class="form-control" 
        name='companyname' 
        description="source" 
        value="{{ old('companyname', isset($location) ? $location->businessname : '' )}}" 
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
        value="{{isset($location) ? $location->address : '' }}" 
        placeholder="street address">
        <span class="help-block">
            <strong>{{ $errors->has('address') ? $errors->first('address') : ''}}</strong>
        </span>
    </div>
</div>
<!-- city -->
<div class="form-group row{{ $errors->has('city') ? ' has-error' : '' }}">
    <label for="city" class="col-md-2 control-label">City: </label>
     <div class="col-sm-8">
        <input required type="text" 
        class="form-control" 
        name='city' 
        description="city" 
        value="{{ old('city', isset($location) ? $location->city : '' )}}" 
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
        value="{{ old('state', isset($location) ? $location->state : '' )}}" 
        placeholder="state">
        <span class="help-block">
            <strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
        </span>
    </div>
</div>
<!-- zip -->
<div class="form-group row{{ $errors->has('zip') ? ' has-error' : '' }}">
    <label for="zip" class="col-md-2 control-label">Zip: </label>
     <div class="col-sm-8">
        <input required type="text" 
        class="form-control" 
        name='zip' 
        description="zip" 
        value="{{ old('zip', isset($location) ? $location->zip : '' )}}" 
        placeholder="zip">
        <span class="help-block">
            <strong>{{ $errors->has('zip') ? $errors->first('zip') : ''}}</strong>
        </span>
    </div>
</div>
<!-- Contact -->
<div class="form-group row{{ $errors->has('contact') ? ' has-error' : '' }}">
    <label for="contact" class="col-md-2 control-label">Contact: </label>
     <div class="col-sm-8">
        <input  type="text" 
        class="form-control" 
        name='contact' 
        description="contact" 
        value="{{ old('contact', isset($location) ? $location->contact : '' )}}" 
        placeholder="contact">
        <span class="help-block">
            <strong>{{ $errors->has('contact') ? $errors->first('contact') : ''}}</strong>
        </span>
    </div>
</div>
<!-- contact Title -->
<div class="form-group row{{ $errors->has('contact_title') ? ' has-error' : '' }}">
    <label for="contact title" class="col-md-2 control-label">Contact title: </label>
     <div class="col-sm-8">
        <input  type="text" 
        class="form-control" 
        name='contact title' 
        description="contact_title" 
        value="{{ old('contact_title', isset($location) ? $location->contact_title : '' )}}" 
        placeholder="contact title">
        <span class="help-block">
            <strong>{{ $errors->has('contact_title') ? $errors->first('contact_title') : ''}}</strong>
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
        value="{{ old('phone', isset($location) ? $location->phone : '' )}}" 
        placeholder="phone">
        <span class="help-block">
            <strong>{{ $errors->has('phone') ? $errors->first('phone') : ''}}</strong>
        </span>
    </div>
</div>
<!-- email -->
<div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email: </label>
     <div class="col-sm-8">
        <input 
        type="email" 
        class="form-control" 
        name='email' 
        description="email" 
        value="{{ old('email', isset($location) ? $location->email : '' )}}" 
        placeholder="email">
        <span class="help-block">
            <strong>{{ $errors->has('email') ? $errors->first('email') : ''}}</strong>
        </span>
    </div>
</div>

