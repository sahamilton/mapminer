@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>Create Role</h3>
	</div>
	<form class="form-horizontal" method="post" action="{{route('roles.store')}}" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->


	@include('admin.roles.partials._form')

		<!-- Form Actions -->
		<div class="form-group">
            <div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-success">Create Role</button>
            </div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop
