@extends('site/layouts/default')
@section('content')
<div class="container">
	<div id='results'>
		<a href="{{route('company.index')}}">Show All Companies</a>
	</div>


	@livewire('company-location-table', ['company_id'=>$company->id])
</div>


@endsection

