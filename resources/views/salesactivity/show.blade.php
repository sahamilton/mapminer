@extends ('site.layouts.default')

@section('content')

<div class="container">
<h1>{{$activity->title}}</h1>
<h4>From {{$activity->datefrom->format('M j Y')}} to {{$activity->dateto->format('M j Y')}}</h4>
<a href="{{route('salescampaigns')}}">
<span class='glyphicon glyphicon-calendar'></span>
Back to all campaigns</a>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Campaign</a></li>
  <li><a data-toggle="tab" href="#menu1">Resources</a></li>
  <li><a data-toggle="tab" href="#menu2">Locations List ({{count($locations)}})</a></li>

</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
   




	<div class="row">
	<h2>Campaign Description</h2>
		<p>{{$activity->description}}</p>
		<div class="col-md-3">

			<h4>Verticals:</h4>
			<?php $verticals = array();?>
			@foreach ($activity->vertical as $vertical)
				@if(! in_array($vertical->filter,$verticals))
				<li> {{$vertical->filter}}</li>
				<?php $verticals[]=$vertical->filter;?>
				@endif
			@endforeach
		</div>
		<div class="col-md-3">
			<h4>Sales Process:</h4>

			<?php $processes = array();?>
			@foreach ($activity->salesprocess as $process)
				@if(! in_array($process->step,$processes))
				<li> {{$process->step}}</li>
				<?php $processes[] = $process->step;?>
				@endif
			@endforeach
		</div>
	</div>
</div>
  <div id="menu1" class="tab-pane fade">
  
<div class="row">
	<h2> Sales Resources</h2>
	@foreach ($activity->relatedDocuments() as $document)

		<h4><a href="{{route('documents.show',$document->id)}}" title="See {{$document->title}} document details">{{$document->title}}</a></h4>
		<p>{{$document->summary}}</p>
		@if(count($document->rankings) >0)
			<?php $rank = round($document->score[0]->score/count($document->rankings));
			$count = count($document->rankings);
			$avg = round($document->score[0]->score/count($document->rankings),2)?>
		@else
			<?php $rank = null;
			$count=0;
			$avg = 0;?>
		@endif
		<div id="{{$document->id}}" data-rating="{{$rank}}" class="starrr" >
         <span id="count-existing"> {{$count}} ratings averaging {{$avg}} </span></div>
		
		@if($document->myranking())
		Your Ranking: {{$document->myranking()->pivot->rank}}
		@endif
	
		<p><a href="{{$document->location}}" target="_blank">View document</a></p>
		<hr />

	@endforeach
</div>
</div>
<div id="menu2" class="tab-pane fade">

	<div class="row">
	<h2>Locations Nearby in these verticals</h2>
		<div class="col-md-10 col-md-offset-1">
			<table class="table" id = "sorttable">
			<thead>
			<th>Company</th>
			<th>Vertical</th>
			<th>Location</th>
			
			<th>Address</th>

			<th>Contact</th>
			<th>Phone</th>


			</thead>
			<tbody>
			@foreach ($locations as $location)

			<tr>
			<td>{{$location->companyname}}</td> 
			<td>{{$location->vertical}}</td>
			<td>{{$location->businessname}}</td>
			
			<td>{!! $location->street . "<br /> " .$location->city. " "   . $location->state !!}</td>
			<td>{{$location->contact }}</td> 
			<td>{{$location->phone }}</td>


			</tr>  

			@endforeach
			</tbody>

			</table>
		</div>
	</div>

</div>
</div>
</div>
@include('partials._scripts')

@endsection
