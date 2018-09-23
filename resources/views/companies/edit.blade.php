@extends('site/layouts/default')

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>Edit National Account</h3>

		<div class="pull-right">
			<a href="{{ route('company.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	
</div>
<form name="editCompany" method="post" action = "{{route('company.update',$company->id)}}">
		{{csrf_field()}}
		<input type="hidden" name="_method" value="patch" />
		@include('companies.partials._form')
		<div class="input-group input-group-lg ">
			<button type="submit" class="btn btn-success">Edit Company</button>
		</div>
	</form>
</div>
@endsection