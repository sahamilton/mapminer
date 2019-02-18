 @if($location->assignedToBranch->count()>0  && array_key_exists($location->assignedToBranch->first()->id,$myBranches))
        <button class="btn btn-success" 
      
          data-toggle="modal" 
          data-target="#createopportunity">Create New Opportunity</button>
      
@elseif($location->opportunities->count()>0)
     <p>Active prospect for {{$location->assignedToBranch->first()->branchname}}</p>
   
@else
     <form name="addBranchLead" method="post" action="{{route('branch.lead.add',$location->id)}}" >
        @csrf
        @if(count($myBranches)==1)
          <input type="submit" class="btn btn-success" value="add to {{array_values($myBranches)[0]}} branch leads" />
          <input type="hidden" name="branch_id" value="{{array_keys($myBranches)[0]}}" >
        @else
          <select name="branch_id" required >
            @if(count($myBranches)>0)
              @foreach($myBranches as $branch_id=>$branch)
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
  @include('opportunities.partials._createmodal')


