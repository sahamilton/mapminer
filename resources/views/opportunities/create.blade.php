@extends('site.layouts.default')
@section('content')
<div class="container">
	<div class="float-right">
		<a href="{{route('opportunity.edit',$opportunity->id)}}" class="btn btn-info" name="editopportunity" >Edit Opportunity</a>
	</div>

<h2><strong>Opportunity </strong>{{$opportunity->title}}</h2>
<p><strong>Branch:</strong>{{$opportunity->branch->branch->branchname}}</p>
<p><strong>Location:</strong>{{$opportunity->address->address->fullAddress()}}</p>
<p><strong>Date Created:</strong>{{$opportunity->created_at->format('Y-m-d')}}</p>
<p><strong>Status:</strong>{{$opportunity->closed}}</p>
<p><strong>Expected Value:</strong>{{$opportunity->value}}</p>
<p><strong>Requirements:</strong></p>{{$opportunity->requirements}}</
<p><strong>Duration:</strong>{{$opportunity->duration}}</p>


</div>

@endsection	