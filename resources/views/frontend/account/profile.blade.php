use Illuminate\Support\Facades\Request;
@extends('frontend/layouts/account')

{{-- Page title --}}
@section('title')
Your Profile
@endsection

{{-- Account page content --}}
@section('account-content')
<div class="page-header">
	<h4>Update your Profile</h4>
</div>

<form method="post" action="" class="form-vertical" autocomplete="off">
	<!-- CSRF Token -->
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />

	<!-- First Name -->
	<div class="control-group{{ $errors->first('first_name', ' error') }}">
		<label class="control-label" for="first_name">First Name</label>
		<div class="controls">
			<input class="span4" type="text" name="first_name" id="first_name" value="{{ Request::old('first_name', $user->first_name) }}" />
			{{ $errors->first('first_name', '<span class="help-block">:message</span>') }}
		</div>
	</div>

	<!-- Last Name -->
	<div class="control-group{{ $errors->first('last_name', ' error') }}">
		<label class="control-label" for="last_name">Last Name</label>
		<div class="controls">
			<input class="span4" type="text" name="last_name" id="last_name" value="{{ Request::old('last_name', $user->last_name) }}" />
			{{ $errors->first('last_name', '<span class="help-block">:message</span>') }}
		</div>
	</div>

	<!-- Website URL -->
	<div class="control-group{{ $errors->first('website', ' error') }}">
		<label class="control-label" for="website">Website URL</label>
		<div class="controls">
			<input class="span4" type="text" name="website" id="website" value="{{ Request::old('website', $user->website) }}" />
			{{ $errors->first('website', '<span class="help-block">:message</span>') }}
		</div>
	</div>

	<!-- Country -->
	<div class="control-group{{ $errors->first('country', ' error') }}">
		<label class="control-label" for="country">Country</label>
		<div class="controls">
			<input class="span4" type="text" name="country" id="country" value="{{ Request::old('country', $user->country) }}" />
			{{ $errors->first('country', '<span class="help-block">:message</span>') }}
		</div>
	</div>

	<!-- Gravatar Email -->
	<div class="control-group{{ $errors->first('gravatar', ' error') }}">
		<label class="control-label" for="gravatar">Gravatar Email <small>(Private)</small></label>
		<div class="controls">
			<input class="span4" type="text" name="gravatar" id="gravatar" value="{{ Request::old('gravatar', $user->gravatar) }}" />
			{{ $errors->first('gravatar', '<span class="help-block">:message</span>') }}
		</div>

		<p>
			<img src="//secure.gravatar.com/avatar/{{ md5(strtolower(trim($user->gravatar))) }}" width="30" height="30" />
			<a href="//gravatar.com">Change your avatar at Gravatar.com</a>.
		</p>
	</div>

	<hr>

	<!-- Form actions -->
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn">Update your Profile</button>
		</div>
	</div>
</form>
@endsection
