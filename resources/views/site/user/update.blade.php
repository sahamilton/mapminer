use Illuminate\Support\Facades\Request;
@extends('site.layouts.default')

{{-- Content --}}
@section('content')
	<div class="container">
<h2>Update Your Profile</h2>
	<form name="profile" method="post" action="{{route('user.update',auth()->user()->id)}}">
{{csrf_field()}}
		
			<!-- firstname -->
				<div class="form-group {!! $errors->has('firstname') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="firstname">First Name</label>
					<div class="input-group input-group-lg ">

						<input required class="form-control" type="text" name="firstname" id="firstname" value="{!! Request::old('firstname', isset($user->person->firstname) ? $user->person->firstname : null) !!}" 
						placeholder="first name"/>
						{!! $errors->first('firstname', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ firstname -->
                
                <!-- lastname -->
				<div class="form-group {!! $errors->has('lastname') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="lastname">Last Name</label>
					<div class="input-group input-group-lg ">

						<input required class="form-control" type="text" name="lastname" id="lastname" value="{!!Request::old('lastname', isset($user->person->lastname) ? $user->person->lastname : null) !!}" 
						placeholder="last name"/>
						{!! $errors->first('lastname', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ lastname -->
		
				
				<!-- Address -->
				<div class="form-group {!! $errors->has('address') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="address">Full Address</label>
					<div class="input-group input-group-lg ">

						<input class="form-control" type="text" 
						placeholder="Full address with city & state"
						name="address" id="address" value="{!!Request::old('address', isset($user) ? $user->person->fullAddress() : null) !!}" 
						/>
						{!! $errors->first('address', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				

				<!-- Phone -->
				<div class="form-group {!! $errors->has('phone') ? 'has-error' : '' !!}">
					<label class="col-md-2 control-label" for="address">Phone</label>
					<div class="input-group input-group-lg ">

						<input class="form-control" type="text" name="phone" id="phone"  value="{!!Request::old('phone', isset($user) ? $user->person->phone : null) !!}" 
						placeholder="phone"/>
						{!! $errors->first('phone', '<span class="help-inline">:message</span>') !!}
					</div>
				</div>
				<!-- ./ phone -->

				<fieldset><legend>Update Your Password</legend>
				<!-- password -->
				    <div class="form-group{{ $errors->has('oldpassword') ? ' has-error' : '' }}">
				        <label class="col-md-2 control-label">Current Password</label>
				            <div class="input-group input-group-lg ">

				                <input type="password" class="form-control" name='oldpassword' description="oldpassword" value="">
				                <span class="help-block">
				                    <strong>{{ $errors->has('oldpassword') ? $errors->first('oldpassword') : ''}}</strong>
				                    </span>
				            </div>
				    </div>
				<!-- password -->
				    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				        <label class="col-md-2 control-label">New Password</label>
				            <div class="input-group input-group-lg ">

				                <input type="password" class="form-control" name='password' description="password" value="">
				                <span class="help-block">
				                    <strong>{{ $errors->has('password') ? $errors->first('password') : ''}}</strong>
				                    </span>
				            </div>
				    </div>

				    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
				        <label class="col-md-2 control-label">Confirm Password</label>
				            <div class="input-group input-group-lg ">

				                <input type="password" class="form-control" name='password_confirmation' description="password_confirmation" value="">
				                <span class="help-block">
				                    <strong>{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation') : ''}}</strong>
				                    </span>
				            </div>
				    </div>
				    </fieldset>
	<input type="submit" name="update" class="btn btn-info" value="Update Your Profile">
	</form>	
            
     </div>
     @endsection       