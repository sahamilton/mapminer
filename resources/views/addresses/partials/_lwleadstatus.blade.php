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
        <div>
          @foreach ($address->assignedToBranch as $branch)
            <p><strong>{{$leadStatuses[$branch->pivot->status_id]}}</strong>
              {{$branch->branchname}}
            </p>
          @endforeach
        </div>
       
            
        <button type="submit"
           class="btn btn-warning"
             wire:click="reassignAddress({{$address->id}})">
             <i class="fas fa-random "></i> Reassign</button>
      
      @include('addresses.partials._lwreassignlead')
  @else

    @foreach ($address->assignedToBranch as $branch)
      @if($branch->pivot->status_id)
        <strong>{{$leadStatuses[$branch->pivot->status_id]}}</strong>
        {{$branch->branchname}}
      @endif
    @endforeach
  @endif
      
  
     

@elseif ($address->claimedByBranch->first())
  

    <strong>Owned By:</strong>
      <a href="{{route('branches.show', $address->claimedByBranch->first()->id)}}">
        {{$branch->branchname}}
      </a>
      @if(auth()->user()->hasRole(['branch_manager']))
      <button 
        class="p-2 btn btn-warning"
        wire:click="requestTransfer({{$address->id}})">
       <i class="text-white fa-solid fa-arrows-cross"></i>
        Request transfer
      </button>
      @endif
   

    
 
  @include('addresses.partials._lwtransferrequest') 
@elseif ($address->assignedToBranch->count())
  Lead currently on offer to:
  @foreach ($address->assignedToBranch as $offered)
    @if(! $loop->last), @endif
      {{$offered->branchname}}
   
  @endforeach
@else

<div class="row m-4">
  @if(count($myBranches) === 1)
      <button wire:click="claimLead({{$myBranches[0]}}, '{{$address->id}}')"
          class="btn btn-success btn-sm"
          title="Claim lead for branch {{$myBranches[0]}}">
         
          Claim Lead
        </button>
     
       
       
  @endif
      
      
    </form>
</div>
@endif

