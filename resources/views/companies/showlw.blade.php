@extends('site/layouts/default')
@section('content')
<div class="container">
	<div id='results'>
		<a href="{{route('company.index')}}">Show All Companies</a>
	</div>

<h2>{{$company->companyname}}</h2>

@if($company->parent_id)
	<p><a href="{{route('company.show',$company->parent_id)}}">See parent company</a></p>
@endif
	@livewire('company-location-table', ['company'=>$company->id])
</div>


@endsection

