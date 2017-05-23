@extends ('site.layouts.default')
@section('content')
<div class="container">
<h1>{{$activity->title}}</h1>
<a href="{{route('salescampaigns')}}">
<span class='glyphicon glyphicon-calendar'></span>
Back to all campaigns</a>
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
<h2> Sales Resources</h2>
@foreach ($activity->relatedDocuments() as $document)
	<h4>{{$document->title}}</h4>
	<p>{{$document->summary}}</p>
	<p><a href="{{$document->link}}" target="_blank">{{$document->link}}</a></p>
	<hr />

@endforeach
</div>
@endsection
