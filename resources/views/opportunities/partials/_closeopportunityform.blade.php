

@foreach($location->opportunities as $opportunity)
<div class="col-sm-6">
	@if($opportunity->closed ==0)
	<a href="#"
	    data-toggle="modal" 
	    data-href="{{route('opportunity.edit',$opportunity->id)}}"
	    data-target="#editopportunity"
	  title = "Review Opportunity Details">
	  <strong>Opportunity {{$opportunity->id}}:  {{$opportunity->title}}</strong> </a>

	<button class="btn btn-danger" 
	      data-href="{{route('opportunity.close',$opportunity->id)}}"
	      data-toggle="modal" 
	      data-target="#closeopportunity">Close</button>
	@endif
</div>
@endforeach
<button class="btn btn-success" 
     	
      data-toggle="modal" 
      data-target="#createopportunity">Create New Opportunity</button>
<!-- Modal -->
@php
$rank =  3 ;@endphp

@include('opportunities.partials._closemodal')
@include('opportunities.partials._editmodal')
@include('opportunities.partials._createmodal')
