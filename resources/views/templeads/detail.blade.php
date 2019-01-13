@extends('site.layouts.default')
@section('content')
<div class="container">
  <h2>{{$lead->businessname ? $lead->businessname : $lead->companyname}}</h2>
  @if($lead->salesteam->count()>0)
 
    <h4>Lead assigned to <a href="{{route('salesrep.newleads',$lead->salesteam->first()->id)}}">{{$lead->salesteam->first()->postName()}}</a></h4>
    @if(auth()->user()->hasRole('Admin'))
    <p text-danger">
              <a data-href="{{route('webleads.unassign',$lead->id)}}" 
                data-toggle="modal" 
                data-target="#unassign-weblead"
                data-title = "unassign this weblead" 
                href="#">

              <i class="fas fa-unlink"></i> Un-assign lead</a></p>

        @include('partials._unassignleadmodal') 
    @endif
    <div class="row">
      <p><strong>Status:</strong>{{$leadStatuses[$lead->salesteam->first()->pivot->status_id]}}</p>
      
    @if((auth()->user()->person->id == $lead->salesteam->first()->id) or (auth()->user()->hasRole('Admin')) && $lead->salesteam->first()->pivot->status_id != 3)
</div>
      <button type="button" class="btn btn-info " data-toggle="modal" data-target="#closelead">
      Close Lead</button>
   
    @else
    <div data-rating="{{$lead->salesteam->first()->pivot->rating}}" 
      class="starrr" 
      style="color:#E77C22">
      <strong style="color:black">Rating:</strong>
    </div>
    @endif
    @include('templeads.partials._closeleadform')
  @else
  <h4>Unassigned</h4>
  <form name="claimlead"
  id="claimlead"
  method="post"
  action="{{route('lead.claim',$lead->id)}}">
  @csrf
  <input type="submit" name="submit" class="btn btn-info" value="Claim Lead"/>
  
  </form>
  @endif
</div>

  <ul class="nav nav-tabs">

      <li class="nav-item">

        <a class="nav-link active" 
        data-toggle="tab" 
        href="#lead"
        role="tab">

          <strong>Lead Details</strong>
        </a>
      </li>
@if(count($extrafields)>0)

  <li class="nav-item">
    <a class="nav-link"  data-toggle="tab" href="#extra">
      <strong>Additional Info</strong>
    </a>
  </li>
@endif


  <li class="nav-item">
    <a class="nav-link"  data-toggle="tab" href="#resources">
      <strong>Nearby Resources</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#notes">
      <strong>Lead  Notes @if($lead->relatedNotes) ({{$lead->relatedNotes->count()}}) @endif
            </strong>
      </a>
    </li>
</ul>

  <?php $type="lead";
  $id= $lead->id;?>
  <div class="tab-content">
    <div id="lead" class="tab-pane fade show active">
      @include('templeads.partials._tabdetails')
    </div>
    @if(count($extrafields)>0)
    <div id="extra" class="tab-pane fade">
      @include('templeads.partials._tabextra')
    </div>
    @endif
    
    <div id="resources" class="tab-pane fade">
      @if($people)
      @include('templeads.partials._tabresources')    
      @endif
      @include('templeads.partials._tabbranches') 
    </div>


    <div id="notes" class="tab-pane fade">
      @include('templeads.partials._tabnotes')
    </div>
   </div>

</div>
</div>


@include('templeads.partials.map')

@include('partials._modal')
@include('partials._scripts');
@endsection
