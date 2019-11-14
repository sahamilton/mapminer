
<div id="createopportunity" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Create {{ $location->businessname }} Opportunity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Create New Opportunity</strong></p>
        <form name="addOpportunity" method="post" action="{{route('opportunity.store')}}" >
        @csrf
        @include('opportunities.partials._opportunityform')
    
        @if($location->assignedToBranch && count(array_intersect( $location->assignedToBranch->pluck('id')->toArray(),$myBranches))==1)
          @php
              
              $branches = $location->assignedToBranch->keyBy('id');
              $branch_id = array_intersect( $location->assignedToBranch->pluck('id')->toArray(),$myBranches);
              
              $assignedBranch = $branches->get(reset($branch_id));
             
          @endphp
        
          <input type="submit" class="btn btn-success" 
          value="add to {{$assignedBranch->branchname}} branch opportunity" />
          <input type="hidden" 
          name="branch_id" 
          value="{{$assignedBranch->id}}" >
        @else
          <select name="branch_id" required >

            @foreach($myBranches as $branch)
            @if(in_array($branch,$location->assignedToBranch->pluck('id')->toArray()))
              <option value="{{$branch}}">{{$branch}}</option>
            @endif
            @endforeach
          </select>
          <input type="submit" class="btn btn-success" value="add to branch opportunity" />
        @endif
        <input type="hidden" value="{{$location->id}}" name="address_id" />
        @php $branch = array_keys($myBranches); 


        @endphp

       
      </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
