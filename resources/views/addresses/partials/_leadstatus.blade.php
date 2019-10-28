@if($location->assignedToBranch->count())
  Assigned to:
  @foreach ($location->assignedToBranch as $branch)

      @if(in_array($branch->id,array_keys($myBranches)))
          @php $owned=true; @endphp
      @endif
      {{$branch->branchname}}
  @endforeach
  
  @if(isset($statuses[$branch->pivot->status_id]))
    <div class="btn-group" role="group" >
      @if($branch->pivot->status_id == 1 && isset($owned))
        
          <form class='form-inline mr-1'
            action = "{{route('branchleads.update',$branch->pivot->id)}}"
            method="post"
            >
            @csrf
            @method('put')
            <button type="submit" class="btn btn-success">
              <i class="far fa-thumbs-up text-white" aria-hidden="true"></i> Accept Lead
            </button>
          </form> 
          <button type="submit" 
              class="btn btn-danger"
              data-href="{{route('branchleads.destroy',$branch->pivot->id)}}" 
              data-toggle="modal" 
              data-target="#decline-lead" 
              data-title = " {{$location->businessname}} lead"
              title = "Reject {{$location->businessname}} lead" 
              href="#">
             <i class="far fa-thumbs-down text-white" aria-hidden="true"></i> 
              Decline Lead
            </button>
         
       
        @include('addresses.partials._declinemodal')
        @elseif ($branch->pivot->status_id == 2 && isset($owned))
            <button class="btn btn-success mr-2" 
      
          data-toggle="modal" 
          data-target="#createopportunity">New Opportunity</button>

          @include('opportunities.partials._createmodal')
            <div class="col-2-md">
              <a class="btn btn-warning"
                   data-toggle="modal" 
                   data-target="#reassign" 
                   
                   href="#">Reassign</a>
            </div>

        @include('addresses.partials._reassignlead')

      @endif
     </div>
  @endif
@else

<form name="claimlead"
  method="post"
  action = "{{route('branchleads.store')}}"
  >
  @csrf
  <input type="hidden" name="address_id" value="{{$location->id}}" />
  @if (count(array_keys($myBranches))==1)
  <input type="hidden" name="branch_id" value = "{{array_keys($myBranches)[0]}}" />
  @endif
  <input type="submit" class="btn btn-success" value="Claim Lead" />
</form>
@endif