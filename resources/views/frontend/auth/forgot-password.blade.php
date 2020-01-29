use Illuminate\Support\Facades\Request;
@extends('site/layouts/default')

{{-- Page title --}}
@section('title')
Forgot Password ::
@parent
@endsection

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>Forgot Password</h3>
</div>
<form method="post" action="" class="form-horizontal">
	<!-- CSRF Token -->
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />

	<!-- Email -->
	<div class="control-group{{ $errors->first('email', ' error') }}">
		<label class="control-label" for="email">Email</label>
		<div class="controls">
			<input type ="email" name="email" id="email" value="{{ Request::old('email') }}" />
			{{ $errors->first('email', '<span class="help-block">:message</span>') }}
		</div>
	</div>

	<!-- Form actions -->
	<div class="control-group">
		<div class="controls">
			<a class="btn" href="{{ route('home') }}">Cancel</a>

			<button type="submit" class="btn">Submit</button>
		</div>
	</div>
</form>
@endsection
