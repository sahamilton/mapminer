@extends ('admin.layouts.default')
@section('content')
<script type="text/javascript" src="{{asset('assets/js/starrr.js')}}"></script>
<div class="container">
			<h2>
				<strong>Prospect Source - {{$leadsource->source}}</strong></h2>
				<p>{{$leadsource->description}}</p>
				<p>
				@if($leadsource->dateto < Carbon\Carbon::now())
            Expired {{$leadsource->datefrom->format('M j,Y')}}
        @elseif ($leadsource->datefrom > Carbon\Carbon::now())
            Commences {{$leadsource->datefrom->format('M j,Y')}}
        @else
           Available from {{$leadsource->datefrom->format('M j,Y')}} to {{$leadsource->dateto->format('M j,Y')}}
        @endif
    </p>

				<p class="row"><a href="{{route('leadsource.export',$leadsource->id)}}"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i>  Export owned and closed {{$leadsource->source}} Leads</a></p>
				

				<p><a href="{{route('leadsource.index')}}">Return to all Prospect sources</a></p>
 @if (auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales Operations'))
<div class="float-right">
                <p><a href="{{{ route('leads.search') }}}" class="btn btn-small btn-info iframe">
<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Import New Web Lead</a></p>
            </div>
 @endif  


	
</ul>
<?php $unassigned = array();?>
<div class="tab-content">
	<div id="map" class="tab-pane fade show active">
	@include('leadsource.partials._tabmap')
	</div>
	<div id="details" class="tab-pane fade in ">
	@include('leadsource.partials._tabdetails')
	</div>
	<div id="team" class="tab-pane fade in ">
	@include('leadsource.partials._tabteam')
	</div>

	
	
	
	<div id="unassigned" class="tab-pane fade in ">

	@include('leadsource.partials._tabunassignedleads')
	</div>
	<div id="stats" class="tab-pane fade in ">
	@include('leadsource.partials._tabstats')
	</div>
</div>
@include('partials._scripts')
@endsection
