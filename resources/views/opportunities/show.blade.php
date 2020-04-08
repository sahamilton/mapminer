@extends('site.layouts.default')
@section('content')
<div class="container">
	<div class="float-right">
		<a href="{{route('opportunity.edit',$opportunity->id)}}" class="btn btn-info" name="editopportunity" >Edit Opportunity</a>
		<a class="btn btn-danger"
        data-href="{{route('opportunity.destroy',$opportunity->id)}}" 
        data-toggle="modal" 
        data-target="#confirm-delete" 
        data-title = " this opportunity from your list" 
        href="#">Delete Opportunity
            <i class="fas fa-trash-alt text-danger"></i></a>
	</div>

<h2><strong>Opportunity </strong>{{$opportunity->title}}</h2>
<p><strong>Branch:</strong>{{$opportunity->branch->branch->branchname}}</p>
<p><strong>Location:</strong>
    <a href="{{route('address.show', $opportunity->address_id)}}">{{$opportunity->address->address->fullAddress()}}</a></p>
<p><strong>Date Created:</strong>{{$opportunity->created_at->format('Y-m-d')}}</p>
<p><strong>Status:</strong>{{$opportunity->closed}}</p>
<p><strong>Expected Value:</strong>{{$opportunity->value}}</p>
<p><strong>Expected Close Date:</strong>@if($opportunity->expected_close) {{$opportunity->expected_close->format('Y-m-d')}} @endif</p>
<p><strong>Requirements:</strong>{{$opportunity->requirements}}</p>
<p><strong>Duration:</strong>{{$opportunity->duration}}</p>
@if($opportunity->csp==1)
<p title="This is an opportunity for the CSP Application Workshop."><strong> <i class="fas fa-clipboard-list text-success"></i> CSP Opportunity</strong></p>
@endif

</div>
@include('partials._modal')
@endsection	