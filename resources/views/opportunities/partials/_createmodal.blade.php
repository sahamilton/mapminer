
<div id="createopportunity" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Create {!! $location->businessname !!} Opportunity  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Create New Opportunity</strong></p>
        <form name="addOpportunity" method="post" action="{{route('opportunity.store')}}" >
        @csrf
        @include('opportunities.partials._opportunityform')
        <!--- this is incorrect  Branches should be imited to the address_branch -->
        @if(count(array_intersect( array_keys($myBranches),$location->assignedToBranch->pluck('id')->toArray()))==1)
          <input type="submit" class="btn btn-success" value="add to {{array_values($myBranches)[0]}} branch opportunity" />
          <input type="hidden" name="branch_id" value="{{$location->assignedToBranch->pluck('id')->toArray()[0]}}" >
        @else
          <select name="branch_id" required >

            @foreach($myBranches as $branch_id=>$branch)
            @if(in_array($branch_id,$location->assignedToBranch->pluck('id')->toArray()))
              <option value="{{$branch_id}}">{{$branch}}</option>
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
