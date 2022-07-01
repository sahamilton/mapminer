<div class="modal fade" 
    id="confirm-transfer-request" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="modalTitle" 
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalTitle">Request Transfer of  {{$address->businessname}} </span></h4>
            </div>
        
            <div class="modal-body">
			    <p>You are requesting that {{$address->businessname}} lead currently associated with branch {{$address->claimedByBranch()->first()->branchname}} be transferred to you branch.  An email will be sent to the {{$address->claimedByBranch()->first()->branchname}}  branch manager. </p>
                <p>Do you wish to proceed?</p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger danger"
                    onclick="event.preventDefault();
                    document.getElementById('action-form').submit();"
                    class="btn btn-danger danger">Request transfer</a>           
                    <form id="action-form" 
                        action="{{route('lead.transferrequest', $address->id)}}" 
                        method="post" 
                        style="display: none;">
                       
                        @csrf
                        <input type="hidden" name="address_id" value="{{$address->id}}" />
                    </form>
            </div>
        </div>
    </div>
</div>