@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
	<div style = "margin-top:30px;">
    
    </div>

	{{-- Create User Form --}}
	<form class="form-horizontal" method="post" action="{{ route('admin.user.store') }}" autocomplete="off">
	@include('admin.users.partials._form')

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				
				<button type="submit" class="btn btn-success">OK</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop
