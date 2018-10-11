@extends ('admin.layouts.default')
@section('content')
<p><a href="{{route('leadsource.show',$lead->lead_source_id)}}">Show All </a></p>
<div class="col-sm-5">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left">{{$lead->company_name}} - {{$lead->rating}}</h2>
			<a class="btn btn-primary pull-right" href="">
<<<<<<< HEAD
				<i class="fa fa-pencil"></i>
=======
				<i class="far fa-edit text-info""></i>
>>>>>>> development
				Edit
			</a>
		</div>
		@include('webleads.partials._detail')

		@if($lead->salesteam->count()>0)
		<div class="list-group">
			<div class="list-group-item">
				<p class="list-group-item-text">Lead Assigned</p>
				<ul style="list-style-type: none;">
						<li><strong>Lead Assigned to:</strong>{{$lead->salesteam->first()->postName()}}</li>
						<li><strong>Lead Assigned on:</strong>{{$lead->salesteam->first()->pivot->created_at->format('j M, Y')}}</li>
						<p class="pull-right text-danger">
							<a data-href="{{route('webleads.unassign',$lead->id)}}" 
			                    data-toggle="modal" 
			                    data-target="#unassign-weblead"
			                    data-title = "unassign this weblead" 
			                    href="#">
<<<<<<< HEAD
							<i class="fa fa-unlink"></i> Un-assign lead</a></p>
=======
							<i class="far fa-unlink"></i> Un-assign lead</a></p>
>>>>>>> development
						
				</ul>
			</div>
		</div>
		@endif
	</div>
@if($lead->salesteam->count()==0)
		@include('leads.partials._branchlist')	
			
		
@endif
</div>		
<div class="col-sm-7 pull-right">
	@include('webleads.partials._search')
<div id="map"  style="border:solid 1px red"></div>
@if($lead->salesteam->count()==0)
@include('webleads.partials.select')
@endif
</div>

@if($lead->salesteam->count()==0)
		<div class="row">
		<div class="col-sm-12">
		@include('leads.partials._repslist')
		</div>
@else
	@include('partials._unassignleadmodal')		
@endif
</div>
	
@include('webleads.partials.map')

@include('partials/_scripts')
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development

