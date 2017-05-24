@extends ('site.layouts.default')
@section('content')
<div class="container">
<h1>{{$activity->title}}</h1>
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
   



<h4>From {{$activity->datefrom->format('M d Y')}} to {{$activity->dateto->format('M d Y')}}</h4>
	<div class="row">
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
		<h4>{{$document->title}}</h4>
		<p>{{$document->summary}}</p>
		<p><a href="{{$document->link}}" target="_blank">{{$document->link}}</a></p>
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

			<th>Location</th>
			<th>Company</th>
			<th>Address</th>

			<th>Contact</th>
			<th>Phone</th>


			</thead>
			<tbody>
			@foreach ($locations as $location)

			<tr> 
			<td>{{$location->businessname}}</td>
			<td>{{$location->companyname}}</td>
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
