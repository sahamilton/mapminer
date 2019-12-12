@if ($owned)

    
      @if($branch->pivot->status_id == 1)
        <div class="col-sm-6">
        <form class='form-inline'
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
             <button type="submit"
              class="btn btn-warning"
                       data-toggle="modal" 
                       data-target="#reassign"
                       href="#">
                       <i class="fas fa-random "></i> Reassign</button>
         </div>       
        @include('addresses.partials._reassignlead')
       
        @include('addresses.partials._declinemodal')
        @elseif ($branch->pivot->status_id == 2)
         
            <div class="col-sm-6">
              @foreach ($location->assignedToBranch as $branch)
                <p><strong>{{$statuses[$branch->pivot->status_id]}}</strong>
                  {{$branch->branchname}}
                </p>
              @endforeach
            </div>
            
            <button class="btn btn-success" 
        
              data-toggle="modal" 
              data-target="#createopportunity">New Opportunity</button>

              @include('opportunities.partials._createmodal')
                
                  <button type="submit"
                     class="btn btn-warning"
                       data-toggle="modal" 
                       data-target="#reassign"
                       href="#">
                       <i class="fas fa-random "></i> Reassign</button>
                
            @include('addresses.partials._reassignlead')
        
        @else
          
          @foreach ($location->assignedToBranch as $branch)
          <li><strong>{{$statuses[$branch->pivot->status_id]}}</strong>
          {{$branch->branchname}}</li>
          @endforeach
        @endif
 
 
     

@elseif ($location->assignedToBranch->count())
  
  @foreach ($location->assignedToBranch as $branch)
    <li><strong>Owned By:</strong>
      <a href="{{route('branches.show', $branch->id)}}">
        {{$branch->branchname}}
      </a>
    </li>
  
  @endforeach
@else
  <form name="claimlead"
    method="post"
    action = "{{route('branchleads.store')}}"
    >
    @csrf
    <input type="hidden" 
    name="address_id" 
    value="{{$location->id}}" />
    @if (count($myBranches)==1)
    <input type="hidden" 
    name="branch_id" 
    value = "{{$myBranches[0]}}" />
    @endif
    <input type="submit" 
    class="btn btn-success" 
    value="Claim Lead" />
  </form>
@endif
