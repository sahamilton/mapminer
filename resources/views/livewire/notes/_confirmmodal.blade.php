

    <div class="modal fade @if($confirmModal) show @endif"
         id="confirmModal"
        @if($confirmModal === true)
            style="overflow-y:auto;display:block;";
         @else
            style="overflow-y:auto;display:none;";
         @endif
         tabindex="-1"
         role="dialog"
         aria-labelledby="modal-title"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete note from {{$address->businessname}}</span> </h5>
                    
                    <button type="button" class="close"  wire:click.prevent="doClose('confirmModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete a note from {{$address->businessname}}.</p>
                    <p class="text text-danger"> Are you sure?</p>
                    
                      <div class="float-right">
                        <button class="btn btn-secondary"
                              type="button"
                              wire:click="doClose('confirmModal')">
                            Cancel
                          </button>
                        <button wire:click.defer="confirmDelete('{{$note->id}}')" class="btn btn-danger">Delete Note</button>
                      
                      </div>
                   
                    
                </div>
                
            </div>
        </div>
    </div>
