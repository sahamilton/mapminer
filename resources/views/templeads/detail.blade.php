@extends('site.layouts.default')
@section('content')
<div class="container">
  <h2>{{$lead->Company_Name}}</h2>
  @if(count($lead->salesrep)>0)
    <h4>Lead assigned to <a href="{{route('salesrep.newleads',$lead->sr_id)}}">{{$lead->salesrep->first()->postName()}}</a></h4>
    <div class="row">
      <p><strong>Status:</strong>{{$leadStatuses[$lead->salesrep->first()->pivot->status_id]}}
    @if((auth()->user()->person->id == $lead->salesrep->first()->id or auth()->user()->hasRole('Admin')) && $lead->salesrep->first()->pivot->status_id != 3)
    
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
    <div id="notes" class="tab-pane fade in">
      @include('templeads.partials._tabnotes')
    </div>
   </div>

</div>


@include('templeads.partials.map')


@include('partials._scripts');
@stop
