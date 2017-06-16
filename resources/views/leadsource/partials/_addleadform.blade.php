<!-- Import file -->
<legend>Either Batch Import from Spreadsheet:</legend>
<div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
     <label for="location" >Upload File Location</label>
     
         <input  type="file" class="form-control" name='file' id='file' description="file" 
         value="{{ old('file')}}">
         <strong>{!! $errors->first('file', '<p class="help-block">:message</p>') !!}</strong>
     </div>
 </div>
<legend>Or Enter Individual Lead:</legend>
<!-- Company Name -->
<div class="form-group{{ $errors->has('companyname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Company</label>
		<div class="input-group input-group-lg ">
		    <input type="text"  class="form-control" name='companyname' description="companyname" value="{{ old('companyname' , isset($lead->companyname) ? $lead->companyname : '') }}" placeholder="companyname">
		    <span class="help-block">
		        <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
		        </span>
		</div>
</div>
<!-- Business Name -->
<div class="form-group{{ $errors->has('businessname') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Busines Name</label>
		<div class="input-group input-group-lg ">
		    <input type="text"  class="form-control" name='businessname' description="businessname" value="{{ old('businessname' , isset($lead->businessname) ? $lead->businessname : '' )}}" placeholder="businessname">
		    <span class="help-block">
		        <strong>{{ $errors->has('businessname') ? $errors->first('businessname') : ''}}</strong>
		        </span>
		</div>
</div>

<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Street Address</label>
		<div class="input-group input-group-lg ">
		    <input type="text"  class="form-control" name='address' description="address" value="{{ old('address' , isset($lead->address) ? $lead->address : '' )}}" placeholder="address">
		    <span class="help-block">
		        <strong>{{ $errors->has('address') ? $errors->first('address') : ''}}</strong>
		        </span>
		</div>
</div>
<!-- City -->
<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">City</label>
		<div class="input-group input-group-lg ">
		   <input type="text"  class="form-control" name='city' description="city" value="{{ old('city' , isset($lead->city) ? $lead->city : '' )}}" placeholder="city">
		   <span class="help-block">
		       <strong>{{ $errors->has('city') ? $errors->first('city') : ''}}</strong>
		       </span>
		</div>
</div>

<!-- State -->
        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">state</label>
        <div class="input-group input-group-lg ">
            <input type="text"  class="form-control" name='state' description="state" value="{{  old('state', isset($lead->state) ? $lead->state : "" )}}" placeholder="state">
            <span class="help-block">
                <strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- zip -->
   <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
	   <label class="col-md-4 control-label">Zip</label>
	   <div class="input-group input-group-lg ">
	       <input type="text"  class="form-control" name='zip' description="zip" value="{{ old('zip' , isset($lead->zip) ? $lead->zip : "" )}}" placeholder="zip">
	       <span class="help-block">
	           <strong>{{ $errors->has('zip') ? $errors->first('zip') : ''}}</strong>
	           </span>
	   </div>
	</div>
	

<!-- Contact -->
	<div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Contact</label>
			<div class="input-group input-group-lg ">
			    <input type="text"  class="form-control" name='contact' description="contact" value="{{ old('contact', isset($lead->contact) ? $lead->contact : "" )}}" placeholder="contact">
			    <span class="help-block">
			        <strong>{{ $errors->has('contact') ? $errors->first('contact') : ''}}</strong>
			        </span>
			</div>
	</div>
<!-- Phone -->
	<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label">Phone</label>
			<div class="input-group input-group-lg ">
			    <input type="text"  class="form-control" name='phone' description="phone" value="{{  old('phone', isset($lead->phone) ? $lead->phone : "" )}}" placeholder="phone">
			    <span class="help-block">
			        <strong>{{ $errors->has('phone') ? $errors->first('phone') : ''}}</strong>
			        </span>
			</div>
	</div>
   <!-- Description --> 
  <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
         <label class="col-md-4 control-label">Description</label>
         <div class="input-group input-group-lg ">
             <textarea class="form-control" name='description' title="description" >
             	{{ old('description', isset($lead->description) ? $lead->description : "") }}


             </textarea>
           
                 <span class="help-block">
                 <strong>{{$errors->has('description') ? $errors->first('description')  : ''}}</strong>
                 </span>
 
         </div>
     </div>


    