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
	@else
	<p><strong>Status:</strong>{{$campaign->status}}</p>
	@endif
	<p><a href="{{route('campaigns.launch', $campaign->id)}}" class="btn btn-warning">Launch / Relaunch Campaign</a></p>
	
	@include('campaigns.partials._summary')
	@include('campaigns.partials._documents')
	@if($campaign->type != 'open')
		@include('campaigns.partials._details')
	@endif
@include ('partials._scripts')
@endsection()