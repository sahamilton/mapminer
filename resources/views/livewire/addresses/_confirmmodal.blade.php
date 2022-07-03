
   
    <div class="modal fade @if($confirmModal) show @endif"
         id="confirmModal"

         style="display: @if($confirmModal === true)
                 block
         @else
                 none
         @endif;"
         tabindex="-1"
         role="dialog"
         aria-labelledby="modal-title"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit <span id="title"> {{isset($location) ? $location->businessname :'company'}} </span> </h5>
                    
                    <button type="button" class="close"  wire:click.prevent="doClose('confirmModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <p>You are about to remove {{$address->businessname}} from your branches leads.</p>
                   
                    
                      <div class="float-right">
                        <button class="btn btn-secondary"
                              type="button"
                              wire:click="doClose('confirmModal')">
                            Cancel
                          </button>
                        <button wire:click.defer="destroyAddress('{{$address->id}}')" class="btn btn-danger">Remove Address</button>
                      
                      </div>
                   
                    
                </div>
                
            </div>
        </div>
    </div>