@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
		<div class="page-header">
		<h3>Create Permission</h3>
	</div>
	{{-- Create permission Form --}}
	<form class="form-horizontal" method="post" action="{{route('permissions.store')}}" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

		<!-- Tabs Content -->
		
			<!-- Tab General -->
			
				<!-- Name -->
				@include('admin.permissions.partials._form')


		<!-- Form Actions -->
		<div class="form-group">
            <div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-success">Create permission</button>
            </div>
		</div>
		<!-- ./ form actions -->
	</form>
@endsection
