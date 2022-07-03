<div class="modal fade @if($requestTransfer) show @endif"
         id="requestTransfer"

         style="display: @if($requestTransfer === true)
                 block
         @else
                 none
         @endif;"
         tabindex="-1"
         role="dialog"
         aria-labelledby="modal-title"
         aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" wire:click="doClose('requestTransfer')" >&times;</button>
                <h4 class="modal-title" id="modalTitle">Request Transfer of  {{$address->businessname}} </span></h4>
            </div>
        
            <div class="modal-body">
			    <p>You are requesting that {{$address->businessname}} lead currently associated with branch {{$address->claimedByBranch()->first()->branchname}} be transferred to your branch.  An email will be sent to the {{$address->claimedByBranch()->first()->branchname}}  branch manager. </p>
                <p>Do you wish to proceed?</p>
            </div>
            
            <div class="float-right">
                <button class="btn btn-secondary"
                      type="button"
                      wire:click="doClose('requestTransfer')">
                    Cancel
                  </button>
                <button wire:click.defer="processTransferRequest('{{$address->id}}')" class="btn btn-warning">Request Transfer</button>
              
              </div>
        </div>
    </div>
</div>