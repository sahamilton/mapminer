@extends ('admin.layouts.default')
@section('content')
 <script type="text/javascript" src="{{asset('assets/js/starrr.js')}}"></script>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left">
				<strong>Prospect Source - {{$leadsource->source}}</strong></h2>
				<p class="row"><a href="{{route('leadsource.export',$leadsource->id)}}"><i class="fa fa-cloud-download" aria-hidden="true"></i></i>  Export owned and closed {{$leadsource->source}} Leads</a></p>
				<p>{{$leadsource->description}}</p>

				<p><a href="{{route('leadsource.index')}}">Return to all Prospect sources</a></p>
		</div>
	</div>
	<div class="list-group">
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>Total Leads:</strong> {{$leadsource->allleads}}</p>
			<p class="list-group-item-text"><strong>Assigned Leads:</strong> {{$leadsource->ownedleads}}</p>
			<p class="list-group-item-text"><strong>Closed Leads:</strong> {{$leadsource->closedleads}}</p>
	
				<p  data-rating="{{round(number_format($leadsource->ranking,2),0)}}" id="rating" class='starrr list-group-item-text'><strong>Average Rating:</strong>  {{number_format($leadsource->ranking,2)}}</p>

		</div>
	</div>
</div>
@include('partials._scripts')
@endsection