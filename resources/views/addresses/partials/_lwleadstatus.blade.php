 
@if (count($owned) > 0)

  @if(isset($branch->pivot) && $branch->pivot->status_id === 2)
   
    
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
      
  
     

@elseif ($address->claimedByBranch->count() >0)

    @ray('herer', $owned, $address->claimedByBranch->first())  
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
 
@else

<div class="row m-4">
  @if(count($myBranches) === 1)
      <button wire:click="claimLead({{$myBranches[0]}}, '{{$address->id}}')"
          class="btn btn-success btn-sm"
          title="Claim lead for branch {{$myBranches[0]}}">
         
          Claim Lead
        </button>
     
       
       
  @endif
</div>
@endif

