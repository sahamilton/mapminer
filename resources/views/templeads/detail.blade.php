@extends('site.layouts.default')
@section('content')
<div class="container">
  <h2>{{$lead->Company_Name}}</h2>
  @if(count($lead->salesteam))
    <h4>Lead assigned to <a href="{{route('salesrep.newleads',$lead->sr_id)}}">{{$lead->salesteam->first()->postName()}}</a></h4>
    <div class="row">
      <p><strong>Status:</strong>{{$leadStatuses[$lead->salesteam->first()->pivot->status_id]}}
    @if((auth()->user()->person->id == $lead->salesteam->first()->id or auth()->user()->hasRole('Admin')) && $lead->salesteam->first()->pivot->status_id != 3)

      <button type="button" class="btn btn-info " data-toggle="modal" data-target="#closelead">
      Close Lead</button>
    </div>
    @endif
    @include('templeads.partials._closeleadform')
  @else
  <h4>Unassigned</h4>
  @endif
</p>

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
    <div id="notes" class="tab-pane fade in">
      @include('templeads.partials._tabnotes')
    </div>
   </div>

</div>


@include('templeads.partials.map')


@include('partials._scripts');
@stop
