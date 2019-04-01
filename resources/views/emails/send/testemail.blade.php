@extends ('admin.layouts.default')
@section('content')
	<h1>Check Email Parse</h1>
	<form name='Posttest' method='post', action = {{route('inbound.email') }} >
		@csrf
		<div class="col-sm-8">
			<div class="form-group">
				<input type='submit' value ='Test by Post' class='btn btn-primary' />
			</div>
		</div>
		<input type="hidden" name="test" value="1" />
	</form>
		<hr />

	<form name='gettest' method='get', action = {{route('testinbound') }} >
		@csrf
		<div class="col-sm-8">
			<div class="form-group">
				<input type='submit' value ='Test by Get' class='btn btn-primary' />
				<input type="hidden" name="test" value="1" />
			</div>
		</div>
	</form>
@endsection