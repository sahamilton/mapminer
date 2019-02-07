<!-- Company Name -->
<div class="form-group{{ $errors->has('companyname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Company</label>
		<div class="input-group input-group-lg ">
		    <input type="text" 
		    @if(!isset($type)) readonly @endif
		    required class="form-control" name='companyname' autocomplete="off" description="companyname" value="{{ old('companyname') ? old('companyname') :isset($lead->companyname) ? $lead->companyname : '' }}" placeholder="companyname">
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
		    @if(!isset($type)) readonly @endif
		    type="text" class="form-control" name='businessname' autocomplete="off" description="businessname" value="{{ old('businessname') ? old('businessname') : isset($lead->businessname) ? $lead->businessname : '' }}" placeholder="businessname">
		    <span class="help-block">
		        <strong>{{ $errors->has('businessname') ? $errors->first('businessname') : ''}}</strong>
		        </span>
		</div>
</div>

<div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Street Address</label>
		<div class="input-group input-group-lg ">
		    <input type="text" 
		     @if(!isset($type)) readonly @endif
		     required 
		     class="form-control" 
		     name='street' 
		     autocomplete="off" 
		     description="street" 
		     value="{{ old('street') ? old('street') : isset($lead) ? $lead->street : '' }}" 
		     placeholder="address">
		    <span class="help-block">
		        <strong>{{ $errors->has('street') ? $errors->first('street') : ''}}</strong>
		        </span>
		</div>
</div>
<!-- City -->
<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">City</label>
		<div class="input-group input-group-lg ">
		   <input type="text" 
		   @if(!isset($type)) readonly @endif
		   required class="form-control" 
		   name='city' 
		   autocomplete="off" 
		   description="city" 
		   value="{{ old('city') ? old('city') : isset($lead->city) ? $lead->city : '' }}" placeholder="city">
		   <span class="help-block">
		       <strong>{{ $errors->has('city') ? $errors->first('city') : ''}}</strong>
		       </span>
		</div>
</div>

<!-- State -->
        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">state</label>
        <div class="input-group input-group-lg ">
            <input type="text" 
             @if(!isset($type)) readonly @endif
             required class="form-control" name='state' 
             autocomplete="off" 
             description="state" 
             value="{{ old('state') ? old('state') : isset($lead->state) ? $lead->state : "" }}" placeholder="state">
            <span class="help-block">
                <strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- zip -->
   <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
	   <label class="col-md-4 control-label">Zip</label>
	   <div class="input-group input-group-lg ">
	       <input type="text" 
	        @if(!isset($type)) readonly @endif 
	       required class="form-control" name='zip' autocomplete="off" description="zip" value="{{ old('zip') ? old('zip') : isset($lead->zip) ? $lead->zip : "" }}" placeholder="zip">
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
			    <input type="text" 
			     @if(!isset($type)) readonly @endif
			     required class="form-control" name='contact' autocomplete="off" 
			    description="contact" 
			    value="{{ old('contact', ! $lead->contacts->isEmpty() ? $lead->contacts->first()->fullname : '') }}" placeholder="contact">
			    <span class="help-block">
			        <strong>{{ $errors->has('contact') ? $errors->first('contact') : ''}}</strong>
			        </span>
			</div>
	</div>

	<!-- Contact -->
	<div class="form-group{{ $errors->has('contact_title') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Title</label>
			<div class="input-group input-group-lg ">
			    <input 
			     @if(!isset($type)) readonly @endif
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
			     @if(!isset($type)) readonly @endif
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
			    @if(!isset($type)) readonly @endif
			    type="text"  class="form-control" name='contactemail' autocomplete="off" description="contactemail" value="{{ old('contactemail', ! $lead->contacts->isEmpty() ? $lead->contacts->first()->email : '') }}" placeholder="contact@company.com">
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
            <select id="leadsource" required autocomplete="off" class="form-control" name='lead_source_id'>

            @foreach ($sources as $key=>$value)
            	<option {{isset($lead) && ($lead->lead_source_id == $key) ? 'selected' : '' }} value="{{$key}}">{{$value}}</option>

            @endforeach


            </select>
            
        </div>
    </div>

<input type="hidden" name="uid" value="{{auth()->user()->id}}" />
<legend>Assign to Branches</legend>
<div class="form-group{{ $errors->has('branch') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Branch</label>
        <div class="input-group input-group-lg ">
            <select id="branch"  multiple autocomplete="off" class="form-control" name='branch[]'>

            @foreach ($branches as $branch)
            	<option selected value="{{$branch->id}}">{{$branch->branchname}}</option>

            @endforeach


            </select>
            
        </div>
    </div>

    