
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
       
        <input type="submit" class="btn btn-success" value="add to branch opportunity" />
        <input type="hidden" value="{{$location->id}}" name="address_id" />
        <input type="hidden" value="{{$branch->id}}" name="branch_id" />
       
      </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
