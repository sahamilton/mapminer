@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Branch Sales Campaigns</h2>


	<div class="float-right">
   		<a href="{{route('campaigns.create')}}" class="btn btn-info">Create New Campaign</a>
   </div>

	<table class="table" id="sorttable">
		<thead>
			<th>Campaign</th>
			<th>Created</th>
			<th>Date From</th>
			<th>Date To</th>
			<th>Author</th>
			<th>Organization</th>
			<th>Status</th>
			<th>Branches</th>
			
			
			<th>Actions</th>
		</thead>
		<tbody>
			@foreach ($campaigns as $campaign)
			
			<tr>
				<td>
					<a href="{{route('campaigns.show',$campaign->id)}}"
						title="See details of this campaign">
						{{$campaign->title}}
					</a>
				</td>
				<td>{{$campaign->created_at->format('Y-m-d')}}</td>
				<td>{{$campaign->datefrom->format('Y-m-d')}}</td>
				<td>{{$campaign->dateto->format('Y-m-d')}}</td>
				<td>@if($campaign->author) {{$campaign->author->fullName()}} @endif</td>
				<td>@if($campaign->manager) {{$campaign->manager->fullName()}} @endif</td>
				<td>{{$campaign->status}}</td>
				<td>{{$campaign->branches_count}}</td>
				
				<td>
					@if($campaign->status == 'planned')
					<a 
					 	data-href="{{route('campaigns.destroy',$campaign->id)}}" 
						data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "campaign"
						title ="Delete this campaign" 
						href="#">

						<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> </a>
					@endif
			</tr>
			@endforeach
		</tbody>
	</table>

@include('partials._modal')
@include ('partials._scripts')
@endsection()