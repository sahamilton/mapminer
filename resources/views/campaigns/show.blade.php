@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Branch Sales Campaign</h2>
	<p><a href="{{route('campaigns.index')}}">Return to all campaigns</a></p>
	@if($campaign->status == 'planned')
	<h4>{{ucwords($campaign->title)}}</h4>
	<div class="float-right">
   		<a href="{{route('campaigns.edit', $campaign->id)}}" class="btn btn-info">Edit Campaign</a>
   </div>
	
	<p><a href="{{route('campaigns.launch', $campaign->id)}}" class="btn btn-info">Launch Campaign</a></p>
	@else
	<p><strong>Status:</strong>{{$campaign->status}}</p>
	@endif
	<p>Descripiton: {{ucwords($campaign->description)}}</p>
	<p><strong>Created By:</strong>{{$campaign->author ? $campaign->author->fullName() :''}}</p>
	<p><strong>Created:</strong>{{$campaign->created_at->format('l jS M Y')}}</p>
	<p><strong>Manager:</strong>
		@if ($campaign->manager)
			{{$campaign->manager->fullName()}}
		@else
			All Managers
		@endif
	</p>
	
	<p><strong>Active From:</strong>{{$campaign->dateto ? $campaign->dateto->format('l jS M Y') : ''}}</p>
	<p><strong>Expires:</strong>{{$campaign->dateto ? $campaign->dateto->format('l jS M Y') : ''}}</p>
	<p><strong>Branches:</strong> <em>(that can service)</em>
		
			{{$data['branches']->count()}}
		
	</p>
	<p>
		@if($campaign->verticals)
			<strong>Verticals:</strong>
			@foreach ($campaign->verticals as $vertical)
				<li>{{$vertical->filter}}</li>
			@endforeach
		@else

			<strong>Companies:</strong>
			@foreach ($campaign->companies as $company)
				
			
				<li>{{$company->companyname}} 
					
					
				</li>
			
			@endforeach
		@endif
	</p>
	@include('campaigns.partials._details')

@include ('partials._scripts')
@endsection()