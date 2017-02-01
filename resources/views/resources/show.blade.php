@extends ('site.layouts.default')
@section('content')

<h1>Sales Resources</h1>
<?php $companies = array();		?>
<h4>How to Sell documents based on your watch list</h4>
@foreach ($watch as $location)
	@if(! in_array($location->watching[0]->company_id, $companies))
			
				<?php $companies[] = $location->watching[0]->company->id;;?>
				<p><a href = "{{route('salesnotes',$location->watching[0]->company_id) }}">
				Read "How to Sell to {{$location->watching[0]->company->companyname}}"</a></p>
			
	@endif
@endforeach





{{-- Scripts --}}
@include('partials._scripts')
@stop
