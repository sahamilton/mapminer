use Illuminate\Support\Facades\Request;
@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
Settings ::
@parent
@endsection


@section('styles')
@parent
body {
	background: #f2f2f2;
}
@endsection

{{-- Content --}}
@section('content')

<div class="page-header">
	<h3>Edit your settings</h3>
</div>
<form class="form-horizontal" method="post" action="{{ route('users.edit', $user->id) }}"  autocomplete="off">
    <!-- CSRF Token -->
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <!-- ./ csrf token -->
    <!-- General tab -->
    <div class="tab-pane active" id="tab-general">
    	<!-- First Name -->
    	<div class="form-group {{{ $errors->has('firstname') ? 'error' : '' }}}">
			
				<label class="col-md-2 control-label"  for="first_name">First Name</label>
				<div class="col-md-10">
					<input class="form-control" type="text" name="firstname" id="firstname" value="{{ Request::old('firstname', $user->person->firstname) }}" />
					{{ $errors->first('firstname', '<span class="help-inline">:message</span>') }}
				</div>
			
		</div>
		<!-- Last Name -->
        <div class="form-group {{{ $errors->has('lastname') ? 'error' : '' }}}">
			
				<label class="col-md-2 control-label" for="last_name">Last Name</label>
				<div class="col-md-10">
					<input class="form-control" type="text" name="lastname" id="lastname" value="{{ Request::old('lastname', $user->person->lastname) }}" />
					{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}
				</div>
			
        </div>
        

        <!-- Email -->
        <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="email">Email</label>
            <div class="col-md-10">
                <input class="form-control" type ="email" name="email" id="email" value="{{{ Request::old('email', $user->email) }}}" />
                {{ $errors->first('email', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ email -->
        
        <!-- address -->
        <div class="form-group {{{ $errors->has('address') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="address">Address</label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="address" id="address" value="{{{ Request::old('address', $user->person->fullAddress()) }}}" />
                {{ $errors->first('address', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ address -->
        
         <!-- phone -->
        <div class="form-group {{{ $errors->has('phone') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="phone">Phone</label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="phone" id="phone" value="{{{ Request::old('phone', $user->person->phone) }}}" />
                {{ $errors->first('phone', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ phone -->
        
        

        <!-- Password -->
        <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="password">Password</label>
            <div class="col-md-10">
                <input class="form-control" type="password" name="password" id="password" value="" />
                {{ $errors->first('password', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ password -->

        <!-- Password Confirm -->
        <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="password_confirmation">Password Confirm</label>
            <div class="col-md-10">
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
                {{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}
            </div>
        </div>
        <!-- ./ password confirm -->
    </div>
    <!-- ./ general tab -->

    <!-- Form Actions -->
    <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </div>
    <!-- ./ form actions -->
</form>
</form>
@endsection
