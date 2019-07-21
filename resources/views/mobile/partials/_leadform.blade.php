<!-- Company Name -->
<div class="form-group{{ $errors->has('companyname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Company</label>
		<div class="input-group input-group-lg ">
		    <input type="text" 
		    required 
            class="form-control" 
		    name='companyname' 
		    autocomplete="off" 
		    description="companyname" 
		    value="{{ old('companyname' , isset($lead->companyname) ? $lead->companyname : '' )}}" 
		    placeholder="companyname">
		    <span class="help-block">
		        <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
		        </span>
		</div>
</div>
<!-- Business Name -->
<div class="form-group{{ $errors->has('businessname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Busines Name</label>
		<div class="input-group input-group-lg ">
		    <input 
		    
		    type="text" 
		    class="form-control" 
		    name='businessname' 
		    autocomplete="off" 
		    description="businessname" 
		    value="{{ old('businessname', isset($lead->businessname) ? $lead->businessname : '' )}}" 
		    placeholder="businessname">
		    <span class="help-block">
		        <strong>{{ $errors->has('businessname') ? $errors->first('businessname') : ''}}</strong>
		        </span>
		</div>
</div>
 <input type="hidden" 
 value="{{ old('street', isset($lead) ? $lead->street : '' )}}"  
 />
		    
<!-- City -->
<input type="hidden" 
value="{{ old('city', isset($lead->city) ? $lead->city : '' )}}" 
/>

<!-- State -->
        
<input type="hidden" 
 value="{{ old('state', isset($lead->state) ? $lead->state : '' )}}"  
 />
           

<!-- zip -->
<input type="hidden" 
value="{{ old('zip', isset($lead->zip) ? $lead->zip : "" )}}" 
/>
	       

<legend>Contact</legend>


<!-- Contact -->
	<div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Contact</label>
			<div class="input-group input-group-lg ">
			    <input type="text" 
			    
			     required class="form-control" name='contact' autocomplete="off" 
			    description="contact" 
			    value="{{ old('contact', ! $lead->contacts->isEmpty() ? $lead->contacts->first()->fullname : '') }}" 
			    placeholder="contact">
			    <span class="help-block">
			        <strong>{{ $errors->has('contact') ? $errors->first('contact') : ''}}</strong>
			        </span>
			</div>
	</div>

	<!-- Contact Title-->
	<div class="form-group{{ $errors->has('contact_title') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Title</label>
			<div class="input-group input-group-lg ">
			    <input 
			
			    type="text" 
			    required class="form-control" 
			    name='contact_title' 
			    autocomplete="off" 
			    description="contact_title" 
			    value="{{ old('contact_title', 
			   ! $lead->contacts->isEmpty() ? $lead->contacts->first()->title : '' )}}" placeholder="contact contact_title">
			    <span class="help-block">
			        <strong>{{ $errors->has('contact_title') ? $errors->first('contact_title') : ''}}</strong>
			        </span>
			</div>
	</div>

<!-- Phone -->
	<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Phone</label>
			<div class="input-group input-group-lg ">
			    <input 
			   
			    type="text" required class="form-control" name='phone' autocomplete="off" description="phone" value="{{ old('phone')  }}" placeholder="phone">
			    <span class="help-block">
			        <strong>{{ $errors->has('phone') ? $errors->first('phone') : ''}}</strong>
			        </span>
			</div>
	</div>

	<!-- contactemail -->
	<div class="form-group{{ $errors->has('contactemail') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Email</label>
			<div class="input-group input-group-lg ">
			    <input 
			 
			    type="text"  class="form-control" name='contactemail' autocomplete="off" description="contactemail" value="{{ old('contactemail', ! $lead->contacts->isEmpty() ? $lead->contacts->first()->email : '') }}" placeholder="contact@company.com">
			    <span class="help-block">
			        <strong>{{ $errors->has('contactemail') ? $errors->first('contactemail') : ''}}</strong>
			        </span>
			</div>
	</div>  