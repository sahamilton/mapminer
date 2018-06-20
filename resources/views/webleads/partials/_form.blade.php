<!-- company_name -->
    <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Company name</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='company_name' description="company_name" value="{{ old('company_name') ? old('company_name') : isset($weblead->company_name) ? $weblead->company_name : "" }}" placeholder="company_name">
                <span class="help-block">
                    <strong>{{ $errors->has('company_name') ? $errors->first('company_name') : ''}}</strong>
                    </span>
            </div>
    </div>

  <!-- address -->
      <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">address</label>
              <div class="col-md-6">
                  <input type="text" class="form-control" name='address' description="address" value="{{ old('address') ? old('address') : isset($weblead->address) ? $weblead->address : "" }}" placeholder="address">
                  <span class="help-block">
                      <strong>{{ $errors->has('address') ? $errors->first('address') : ''}}</strong>
                      </span>
              </div>
      </div>
  <!-- city -->
      <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">city</label>
              <div class="col-md-6">
                  <input type="text" class="form-control" name='city' description="city" value="{{ old('city') ? old('city') : isset($weblead->city) ? $weblead->city : "" }}" placeholder="city">
                  <span class="help-block">
                      <strong>{{ $errors->has('city') ? $errors->first('city') : ''}}</strong>
                      </span>
              </div>
      </div>
  <!-- state -->
      <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">state</label>
              <div class="col-md-6">
                  <input type="text" class="form-control" name='state' description="state" value="{{ old('state') ? old('state') : isset($weblead->state) ? $weblead->state : "" }}" placeholder="state">
                  <span class="help-block">
                      <strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
                      </span>
              </div>
      </div>
              
<!-- zip -->
    <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">zip</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='zip' description="zip" value="{{ old('zip') ? old('zip') : isset($weblead->zip) ? $weblead->zip : "" }}" placeholder="zip">
                <span class="help-block">
                    <strong>{{ $errors->has('zip') ? $errors->first('zip') : ''}}</strong>
                    </span>
            </div>
    </div>
    


<!-- first_name -->
    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">first_name</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='first_name' description="first_name" value="{{ old('first_name') ? old('first_name') : isset($weblead->first_name) ? $weblead->first_name : "" }}" placeholder="first_name">
                <span class="help-block">
                    <strong>{{ $errors->has('first_name') ? $errors->first('first_name') : ''}}</strong>
                    </span>
            </div>
    </div>
    
<!-- last_name -->
    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">last_name</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='last_name' description="last_name" value="{{ old('last_name') ? old('last_name') : isset($weblead->last_name) ? $weblead->last_name : "" }}" placeholder="last_name">
                <span class="help-block">
                    <strong>{{ $errors->has('last_name') ? $errors->first('last_name') : ''}}</strong>
                    </span>
            </div>
    </div>
<!-- contacttitle -->
    <div class="form-group{{ $errors->has('contacttitle') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">contacttitle</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='contacttitle' description="contacttitle" value="{{ old('contacttitle') ? old('contacttitle') : isset($weblead->contacttitle) ? $weblead->contacttitle : "" }}" placeholder="contacttitle">
                <span class="help-block">
                    <strong>{{ $errors->has('contacttitle') ? $errors->first('contacttitle') : ''}}</strong>
                    </span>
            </div>
    </div>
    
 <!-- email_address -->
     <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
         <label class="col-md-4 control-label">email_address</label>
             <div class="col-md-6">
                 <input type="text" class="form-control" name='email_address' description="email_address" value="{{ old('email_address') ? old('email_address') : isset($weblead->email_address) ? $weblead->email_address : "" }}" placeholder="email_address">
                 <span class="help-block">
                     <strong>{{ $errors->has('email_address') ? $errors->first('email_address') : ''}}</strong>
                     </span>
             </div>
     </div>
     
<!-- phone_number -->
    <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">phone_number</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='phone_number' description="phone_number" value="{{ old('phone_number') ? old('phone_number') : isset($weblead->phone_number) ? $weblead->phone_number : "" }}" placeholder="phone_number">
                <span class="help-block">
                    <strong>{{ $errors->has('phone_number') ? $errors->first('phone_number') : ''}}</strong>
                    </span>
            </div>
    </div>
 <!-- jobs -->
        <div class="form-group{{ $errors->has('jobs') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">jobs</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name='jobs' description="jobs" value="{{ old('jobs') ? old('jobs') : isset($weblead->jobs) ? $weblead->jobs : "" }}" placeholder="jobs">
                    <span class="help-block">
                        <strong>{{ $errors->has('jobs') ? $errors->first('jobs') : ''}}</strong>
                        </span>
                </div>
        </div>
           
@php $ratings =['hot','medium','cold'];@endphp
		<div class="form-group{{ $errors->has('rating)') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Ratings</label>
        <div class="col-md-6">
			<select class="form-control" name='rating'>

				@foreach ($ratings as $rating))
					@if($weblead && $weblead->rating == $rating)
						<option selected  value="{{$rating}}">{{$rating}}</option>
					@else
						<option value="{{$rating}}">{{$rating}}</option>
					@endif
				@endforeach
			</select>
            <span class="help-block">
                <strong>{{ $errors->has('rating') ? $errors->first('rating') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- time_frame -->
    <div class="form-group{{ $errors->has('time_frame') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">time_frame</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='time_frame' description="time_frame" value="{{ old('time_frame') ? old('time_frame') : isset($weblead->time_frame) ? $weblead->time_frame : "" }}" placeholder="time_frame">
                <span class="help-block">
                    <strong>{{ $errors->has('time_frame') ? $errors->first('time_frame') : ''}}</strong>
                    </span>
            </div>
    </div>

<!-- industry -->
    <div class="form-group{{ $errors->has('industry') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">industry</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='industry' description="industry" value="{{ old('industry') ? old('industry') : isset($weblead->industry) ? $weblead->industry : "" }}" placeholder="industry">
                <span class="help-block">
                    <strong>{{ $errors->has('industry') ? $errors->first('industry') : ''}}</strong>
                    </span>
            </div>
    </div>
    
 