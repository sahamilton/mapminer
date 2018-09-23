@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
		<div class="page-header">
		<h3>Edit Role</h3>
	</div>
	

	{{-- Edit Role Form --}}
	<form class="form-horizontal" method="post" action="{{route('roles.update',$role->id)}}" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<input type="hidden" name="_method" value="put" />
		<!-- ./ csrf token -->

		@include('admin.roles.partials._form')

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">

				<button type="submit" class="btn btn-success">Update Role</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@endsection
