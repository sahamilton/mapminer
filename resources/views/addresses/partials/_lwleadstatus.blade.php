@if ($owned)

  @if($branch->pivot->status_id === 1)
   
    <div class="d-flex flex-row">
      <form class='p-2 form-inline'
          action = "{{route('branchleads.update',$branch->pivot->id)}}"
          method="post"
          >
          @csrf
          @method('put')
          <button type="submit" 
          class="p-2 btn btn-success">
            <i class="far fa-thumbs-up text-white" aria-hidden="true"></i> Accept Lead
          </button>
      </form>
      <button type="submit" 
        class="p-2 btn btn-danger"
        data-href="{{route('branchleads.destroy',$branch->pivot->id)}}" 
        data-toggle="modal" 
        data-target="#decline-lead" 
        data-title = " {{$address->businessname}} lead"
        title = "Reject {{$address->businessname}} lead" 
        href="#">
       <i class="far fa-thumbs-down text-white" aria-hidden="true"></i> 
        Decline Lead
      </button>
    </div>
   
    @include('addresses.partials._declinemodal')
  @elseif ($branch->pivot->status_id == 2)
      @if(isset($campaigns) && $campaigns->count() >0)
        @include('addresses.partials._lwcampaigns')
      @endif
        <div class="col-sm-6">
          @foreach ($address->assignedToBranch as $branch)
            <p><strong>{{$leadStatuses[$branch->pivot->status_id]}}</strong>
              {{$branch->branchname}}
            </p>
          @endforeach
        </div>
       
            
        <button type="submit"
           class="btn btn-warning"
             data-toggle="modal" 
             data-target="#reassign"
             href="#">
             <i class="fas fa-random "></i> Reassign</button>
      
      @include('addresses.partials._lwreassignlead')
  @else
    @foreach ($address->assignedToBranch as $branch)
      @if($branch->pivot->status_id)
        <li><strong>{{$leadStatuses[$branch->pivot->status_id]}}</strong>
        {{$branch->branchname}}</li>
      @endif
    @endforeach
  @endif
      
  
     

@elseif ($address->claimedByBranch->first())
  
 

    <strong>Owned By:</strong>
      <a href="{{route('branches.show', $address->claimedByBranch->first()->id)}}">
        {{$branch->branchname}}
      </a>

      <button type="submit" 
        class="p-2 btn btn-warning"
        
        data-toggle="modal" 
        data-target="#confirm-transfer-request" 
         
        href="#">
       <i class="text-white fa-solid fa-arrows-cross"></i>
        Request transfer
      </button>
      
   

    
 
  @include('addresses.partials._lwtransferrequest') 
@elseif ($address->assignedToBranch->count())
  Lead currently on offer to:
  @foreach ($address->assignedToBranch as $offered)
    @if(! $loop->last), @endif
      {{$offered->branchname}}
   
  @endforeach
@else

<div class="row mb-4">
   <form name="claimlead"
      class="form-inline"
      method="post"
      action = "{{route('branchleads.store')}}"
      >
      @csrf
      <button type="submit" 
          class="btn btn-success btn-sm"
          title="Claim lead for branch">
         
          Claim Lead
        </button>
      <input type="hidden" 
        name="address_id" 
        value="{{$address->id}}" /> 
        
      @if(count($myBranches)===1)
        <input type="hidden" 
          name="branch_id" 
          value = "{{$myBranches[0]}}" />
       
      @else 
        for branch:
          <select  class="form-control mb-2 mx-sm-2" name="branch_id" required >

          @foreach ($myBranches as $myBranch)
            <option value="{{$myBranch}}">{{$myBranch}}</option>

          @endforeach

        </select>
       
       
      @endif
      
      
    </form>
</div>
@endif

