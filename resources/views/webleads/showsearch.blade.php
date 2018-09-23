@extends ('admin.layouts.default')
@section('content')
<p><a href="{{route('leadsource.show',$lead->lead_source_id)}}">Show All WebLeads</a></p>
<div class="col-sm-5">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left">{{$lead->address}}</h2>
		</div>	
		
	</div>
	<style>
	ul{list-style-type: none;}
</style>
		@include('leads.partials._branchlist')	
		@include('leads.partials._repslist')
</div>		
<div class="col-sm-7 pull-right">
	@include('webleads.partials._search')
<div id="map"  style="border:solid 1px red"></div>



</div>


		

</div>
	
@include('webleads.partials.map')

@include('partials/_scripts')
@endsection

