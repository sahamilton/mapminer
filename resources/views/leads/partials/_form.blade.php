<!-- Company Name -->
<div class="form-group{{ $errors->has('companyname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Company</label>
		<div class="input-group input-group-lg ">
		    <input type="text" required class="form-control" name='companyname' description="companyname" value="{{ old('companyname') ? old('companyname') :isset($lead->companyname) ? $lead->companyname : '' }}" placeholder="companyname">
		    <span class="help-block">
		        <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
		        </span>
		</div>
</div>
<!-- Business Name -->
<div class="form-group{{ $errors->has('businessname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Busines Name</label>
		<div class="input-group input-group-lg ">
		    <input type="text" class="form-control" name='businessname' description="businessname" value="{{ old('businessname') ? old('businessname') : isset($lead->businessname) ? $lead->businessname : '' }}" placeholder="businessname">
		    <span class="help-block">
		        <strong>{{ $errors->has('businessname') ? $errors->first('businessname') : ''}}</strong>
		        </span>
		</div>
</div>

<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Street Address</label>
		<div class="input-group input-group-lg ">
		    <input type="text" required class="form-control" name='address' description="address" value="{{ old('address') ? old('address') : isset($lead->address->address) ? $lead->address->address : '' }}" placeholder="address">
		    <span class="help-block">
		        <strong>{{ $errors->has('address') ? $errors->first('address') : ''}}</strong>
		        </span>
		</div>
</div>
<!-- City -->
<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">City</label>
		<div class="input-group input-group-lg ">
		   <input type="text" required class="form-control" name='city' description="city" value="{{ old('city') ? old('city') : isset($lead->address->city) ? $lead->address->city : '' }}" placeholder="city">
		   <span class="help-block">
		       <strong>{{ $errors->has('city') ? $errors->first('city') : ''}}</strong>
		       </span>
		</div>
</div>

<!-- State -->
        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">state</label>
        <div class="input-group input-group-lg ">
            <input type="text" required class="form-control" name='state' description="state" value="{{ old('state') ? old('state') : isset($lead->address->state) ? $lead->address->state : "" }}" placeholder="state">
            <span class="help-block">
                <strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- zip -->
   <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
	   <label class="col-md-4 control-label">Zip</label>
	   <div class="input-group input-group-lg ">
	       <input type="text" required class="form-control" name='zip' description="zip" value="{{ old('zip') ? old('zip') : isset($lead->address->zip) ? $lead->address->zip : "" }}" placeholder="zip">
	       <span class="help-block">
	           <strong>{{ $errors->has('zip') ? $errors->first('zip') : ''}}</strong>
	           </span>
	   </div>
	</div>
	

<legend>Contact</legend>


<!-- Contact -->
	<div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Contact</label>
			<div class="input-group input-group-lg ">
			    <input type="text" required class="form-control" name='contact' description="contact" value="{{ old('contact') ? old('contact') : isset($lead->contacts) ? $lead->contacts->contact : "" }}" placeholder="contact">
			    <span class="help-block">
			        <strong>{{ $errors->has('contact') ? $errors->first('contact') : ''}}</strong>
			        </span>
			</div>
	</div>

	<!-- Contact -->
	<div class="form-group{{ $errors->has('contacttitle') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Contact</label>
			<div class="input-group input-group-lg ">
			    <input type="text" required class="form-control" name='contacttitle' description="contacttitle" value="{{ old('contacttitle') ? old('contact') : isset($lead->contacts) ? $lead->contacts->contacttitle : "" }}" placeholder="contact title">
			    <span class="help-block">
			        <strong>{{ $errors->has('contacttitle') ? $errors->first('contacttitle') : ''}}</strong>
			        </span>
			</div>
	</div>

<!-- Phone -->
	<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Phone</label>
			<div class="input-group input-group-lg ">
			    <input type="text" required class="form-control" name='phone' description="phone" value="{{ old('phone') ? old('phone') : isset($lead->contacts) ? $lead->contacts->contactphone : "" }}" placeholder="phone">
			    <span class="help-block">
			        <strong>{{ $errors->has('phone') ? $errors->first('phone') : ''}}</strong>
			        </span>
			</div>
	</div>

	<!-- Phone -->
	<div class="form-group{{ $errors->has('contactemail') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Email</label>
			<div class="input-group input-group-lg ">
			    <input type="text" required class="form-control" name='contactemail' description="contactemail" value="{{ old('contactemail') ? old('contactemail') : isset($lead->contacts->contactemail) ? $lead->contacts->contactemail : "" }}" placeholder="contact email">
			    <span class="help-block">
			        <strong>{{ $errors->has('contactemail') ? $errors->first('contactemail') : ''}}</strong>
			        </span>
			</div>
	</div>
   @include('leads.partials._extrafields')
     

<legend>Prospect Source</legend>   
		<div class="form-group{{ $errors->has('lead_source_id') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Prospect Source</label>
        <div class="input-group input-group-lg ">
            <select id="leadsource" required class="form-control" name='lead_source_id'>

            @foreach ($sources as $key=>$value)
            	<option {{isset($lead) && ($lead->lead_source_id == $key) ? 'selected' : '' }} value="{{$key}}">{{$value}}</option>

            @endforeach


            </select>
            
        </div>
    </div>


    