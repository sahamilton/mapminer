@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
	<div style = "margin-top:30px;">

    </div>


	{{-- Edit User Form --}}

	<form class="form-horizontal" method="post" action="{{ route('users.update' , $user->id ) }}" autocomplete="off">
	<input type="hidden" name="_method" value="put" />
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value ="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->
		@include('admin.users.partials._form')
		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				
				<button type="submit" class="btn btn-success">OK</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@endsection
