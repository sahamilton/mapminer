@extends ('admin.layouts.default')
@section('content')
<h2>Lead Details!!</h2>
<p><a href="{{route('leadsource.show',$lead->lead_source_id)}}">Show All </a></p>
<div class="col-sm-5">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left">{{$lead->companyname}} - {{$lead->rating}}</h2>
			<a class="btn btn-primary pull-right" href="{{route('leads.edit',$lead->id)}}">
				<i class="fa fa-pencil"></i>
				Edit
			</a>
		</div>
		@include('leads.partials._detail')

		@if(count($lead->salesteam)>0)

		<div class="list-group">
			<div class="list-group-item">
				<p class="list-group-item-text">Lead Assigned</p>
				<ul style="list-style-type: none;">
						<li><strong>Lead Assigned to:</strong>{{$lead->salesteam->first()->postName()}}</li>
						<li><strong>Lead Assigned on:</strong>{{$lead->salesteam->first()->pivot->created_at->format('j M, Y')}}</li>
						<li><strong>Branch Assignment:</strong>{{$lead->branches->branchname}}</li>
						<p class="pull-right text-danger">
							<a data-href="{{route('webleads.unassign',$lead->id)}}" 
			                    data-toggle="modal" 
			                    data-target="#unassign-weblead"
			                    data-title = "unassign this weblead" 
			                    href="#">
							<i class="fa fa-unlink"></i> Un-assign lead</a></p>
						
				</ul>
			</div>
		</div>
		@endif
	</div>

</div>		
<div class="col-sm-7 pull-right">
	@include('webleads.partials._search')
<div id="map"  style="border:solid 1px red"></div>

</div>


</div>
	
@include('webleads.partials.map')

@include('partials/_scripts')
@stop

