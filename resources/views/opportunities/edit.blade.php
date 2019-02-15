@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>Edit Opportunity</h2>
<form method="post" name="editOpportunity" action="{{route('opportunity.update',$opportunity->id)}}" >
	@csrf
	@method('put')
	@include('opportunities.partials._opportunityform')
	<input type="submit" name="submit" value="Edit Opportunity" class="btn btn-info">
</form>



</div>

@endsection	