  
  @if($location->assignedToBranch->count()>0)

      @if($location->opportunities->count()>0)

        @if(array_key_exists($location->assignedToBranch->first()->id,$mybranches))
        <button class="btn btn-success" 
      
      data-toggle="modal" 
      data-target="#createopportunity">Create New Opportunity</button>
      @include('opportunities.partials._createmodal')

        @else
        <p>Active prospect for {{$location->assignedToBranch->first()->branchname}}</p>
        @endif
      @else
        @if(array_key_exists($location->assignedToBranch->first()->id,$mybranches))

      <form name="addOpportunity" method="post" action="{{route('opportunity.store')}}" >
        @csrf
        @if(count($mybranches)==1)
          <input type="submit" class="btn btn-success" value="add to {{array_values($mybranches)[0]}} branch opportunity" />
          <input type="hidden" name="branch_id" value="{{array_keys($mybranches)[0]}}" >
        @else
          <select name="branch_id" required >

            @foreach($mybranches as $branch_id=>$branch)
              <option value="{{$branch_id}}">{{$branch}}</option>

            @endforeach
          </select>
          <input type="submit" class="btn btn-success" value="add to branch opportunity" />
        @endif
        <input type="hidden" value="{{$location->id}}" name="address_id" />
        
      </form>

        @else
        <p>Assigned to {{$location->assignedToBranch->first()->branchname}}</p>
        @endif
      @endif

    @else
     <form name="addBranchLead" method="post" action="{{route('branch.lead.add',$location->id)}}" >
        @csrf
        @if(count($mybranches)==1)
          <input type="submit" class="btn btn-success" value="add to {{array_values($mybranches)[0]}} branch opportunity" />
          <input type="hidden" name="branch_id" value="{{array_keys($mybranches)[0]}}" >
        @else
          <select name="branch_id" required >
            @if(count($mybranches)>0)
            @foreach($mybranches as $branch_id=>$branch)
              <option value="{{$branch_id}}">{{$branch}}</option>

            @endforeach
            @else
            @foreach($branches as $branch)
            <option value="{{$branch->id}}">{{$branch->branchname}}</option>
            @endforeach

            @endif
          </select>
          <input type="submit" class="btn btn-success" value="add to branch leads" />
        @endif
        <input type="hidden" value="{{$location->id}}" name="address_id" />
        
      </form>
  @endif

