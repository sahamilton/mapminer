@extends ('site.layouts.default')
@section('content')
<h2>Web Lead Assigned to {{$person->fullName()}}</h2>
<h4><a href="{{route('salesrep.newleads',$person->id)}}">Return to all web leads</a></h4>
<div class="col-sm-5">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left"><strong>{{$lead->companyname}}</strong> - {{$lead->rating}}</h2>
			<div class="float-right">
				@if($lead->salesteam->first()->pivot->status_id != 3)
			
				<button type="button" class="btn btn-info " data-toggle="modal" data-target="#closelead">
      			Close Lead
	      		</button>
	      		@include('webleads.partials._closeleadform')
	      	
	      	@else
	      		<button disabled type="button" class="btn btn-success" >Closed</button>
	      	@endif

	      </div>

		</div>
		@include('webleads.partials._detail')
		@include('webleads.partials._notes')
	</div>

</div>		
<div class="col-sm-7 float-right">
<div id="map"  style="border:solid 1px red"></div>
</div>
	

@include('webleads.partials.salesmap')
@include('partials._modal')
@include('partials/_scripts')
@endsection

