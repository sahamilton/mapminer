@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
	
	<div class="page-header">
		<h3>Edit Permission</h3>
	</div>
	{{-- Edit permission Form --}}
	<form class="form-horizontal" method="post" action="{{route('permissions.update',$permission->id)}}" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<input type="hidden" name="_method" value="put" />
		<inputtype="hidden" name="permission_id" value="{{$permission->id}}" />
		<!-- ./ csrf token -->
				@include('admin.permissions.partials._form')


		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				
				<button type="submit" class="btn btn-success">Update permission</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop
