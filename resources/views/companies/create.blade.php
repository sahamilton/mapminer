@extends('site/layouts/default')

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>Create a New National Account</h3>

	<div class="float-right">
		<a href="{{ route('company.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
	</div>

</div>

<!-- Tabs -->
<div class="container">
	<form name="createCompany" method="post" action = "{{route('company.store')}}">
		{{csrf_field()}}
		@include('companies.partials._form')
		<div class="input-group input-group-lg ">
			<button type="submit" class="btn btn-success">Create Company</button>
		</div>
	</form>
</div>
@endsection
