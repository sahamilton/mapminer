@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Branch Sales Campaign</h2>
	<p><a href="{{route('campaigns.index')}}">Return to all campaigns</a></p>

	<h4>{{ucwords($campaign->title)}}</h4>
	<div class="float-right">
   		<a href="{{route('campaigns.edit', $campaign->id)}}" class="btn btn-info">Edit Campaign</a>
   </div>

	<p>Descripiton: {{ucwords($campaign->description)}}</p>
	<p><strong>Created By:</strong>{{$campaign->author ? $campaign->author->fullName() :''}}</p>
	<p><strong>Created:</strong>{{$campaign->created_at->format('l jS M Y')}}</p>
	<p><strong>Manager:</strong>{{$campaign->manager->fullName()}}</p>
	
	<p><strong>Active From:</strong>{{$campaign->dateto ? $campaign->dateto->format('l jS M Y') : ''}}</p>
	<p><strong>Expires:</strong>{{$campaign->dateto ? $campaign->dateto->format('l jS M Y') : ''}}</p>
	<p><strong>Branches:</strong>
		<a href="{{route('campaign.details', $campaign->id)}}">
			{{$campaign->branches->count()}}
		</a>
	</p>
	<p>
		@if($campaign->verticals)
			<strong>Verticals:</strong>
			@foreach ($campaign->verticals as $vertical)
				<li>{{$vertical->filter}}</li>
			@endforeach
		@else

			<strong>Companies:</strong>
			@foreach ($comps as $company)
				@foreach ($company as $companyname=>$loccount)
			
				<li>{{$companyname}} 
					({{$loccount}} locations) 
					
				</li>
				@endforeach
			@endforeach
		@endif
	</p>


@include ('partials._scripts')
@endsection()