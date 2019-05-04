@extends('admin.layouts.default')
@section('content')
<div class="container">

<h4>Select Company to add locations to {{$leadsource->title}} leadsource</h4>
	<form name="selectcompany" 
		action="{{route('leadsource.addcompanylocations',$leadsource->id)}}"
		method="post">
	@csrf
	<select name="company_id" required>
		@foreach($companies as $company)
		<option value="{{$company->id}}">{{$company->companyname}} 
			<span class="text-danger">
				({{$company->locations_count}})
			</span>
		</option>
		@endforeach
	</select>
	<input type="submit" 
			name="submit" 
			value="Add Selected Company Locations" 
			class="btn btn-success" />
	</form>

</div>
@include('partials._scripts')
@endsection