<!-- Business Name -->
<div class="form-group{{ $errors->has('businessname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Business Name</label>
		<div class="input-group input-group-lg ">
		    <input 
			    type="text" 
			    class="form-control" 
			    name='companyname'
			    required 
			    autocomplete="off" 
			    description="businessname" 
			    value="{{ old('businessname', '' )}}" 
			    placeholder="businessname">
		    <span class="help-block">
		        <strong>
		        	{{ $errors->has('businessname') ? $errors->first('businessname') : ''}}
		        </strong>
		    </span>
		</div>
</div>

<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Address</label>
		<div class="input-group input-group-lg ">
		<input type="text"
		class="form-control" 
		required 
		name="address" 
		value="{{session()->has('geo.address') ? session('geo.address') : ''}}"
		placeholder = 'Full address' />

   </div>
</div>
