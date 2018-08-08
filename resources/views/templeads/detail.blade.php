@extends('site.layouts.default')
@section('content')
<div class="container">
  <h2>{{$lead->businessname ? $lead->businessname : $lead->companyname}}</h2>
  @if(count($lead->salesteam))
    <h4>Lead assigned to <a href="{{route('salesrep.newleads',$lead->salesteam->first()->id)}}">{{$lead->salesteam->first()->postName()}}</a></h4>
    @if(auth()->user()->hasRole('Admin'))
<p  text-danger">
              <a data-href="{{route('webleads.unassign',$lead->id)}}" 
                          data-toggle="modal" 
                          data-target="#unassign-weblead"
                          data-title = "unassign this weblead" 
                          href="#">
              <i class="fa fa-unlink"></i> Un-assign lead</a></p>
@include('partials._unassignleadmodal') 
    @endif
    <div class="row">
      <p><strong>Status:</strong>{{$leadStatuses[$lead->salesteam->first()->pivot->status_id]}}</p>
      
    @if((auth()->user()->person->id == $lead->salesteam->first()->id or auth()->user()->hasRole('Admin')) && $lead->salesteam->first()->pivot->status_id != 3)

      <button type="button" class="btn btn-info " data-toggle="modal" data-target="#closelead">
      Close Lead</button>
    </div>
    @else
    <div data-rating="{{$lead->salesteam->first()->pivot->rating}}" class="starrr" style="color:#E77C22"><strong style="color:black">Rating:</strong></div>
    @endif
    @include('templeads.partials._closeleadform')
  @else
  <h4>Unassigned</h4>
  @endif


  <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#lead">
          <strong>Lead Details</strong>
        </a>
      </li>
@if(count($extrafields))
<li>
        <a data-toggle="tab" href="#extra">
          <strong>Additional Info</strong>
        </a>
      </li>
@endif


<li>
        <a data-toggle="tab" href="#resources">
          <strong>Nearby Resources</strong>
        </a>
      </li>

      <li>
        <a data-toggle="tab" href="#notes">
          <strong>Lead  Notes @if(count($lead->relatedNotes)>0) ({{count($lead->relatedNotes)}}) @endif
          </strong>
        </a>
      </li>
      
      
      



    </ul>
  <?php $type="lead";
  $id= $lead->id;?>
  <div class="tab-content">
    <div id="lead" class="tab-pane fade in active">
      @include('templeads.partials._tabdetails')
    </div>
    @if(count($extrafields))
    <div id="extra" class="tab-pane fade in">
      @include('templeads.partials._tabextra')
    </div>
    @endif
    
    <div id="resources" class="tab-pane fade in">
      @if($people)
      @include('templeads.partials._tabresources')    
      @endif
      @include('templeads.partials._tabbranches') 
    </div>


    <div id="notes" class="tab-pane fade in">
      @include('templeads.partials._tabnotes')
    </div>
   </div>

</div>
</div>

@include('templeads.partials.map')

@include('partials._modal')
@include('partials._scripts');
@stop
