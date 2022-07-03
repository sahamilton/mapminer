
   
    <div class="modal fade @if($confirmContact) show @endif"
         id="confirmContact"

         style="display: @if($confirmContact === true)
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
                    <h5 class="modal-title">Delete <span id="title"> {{isset($contact) ? $contact->fullname :'this contact'}} </span> </h5>
                    
                    <button type="button" class="close"  wire:click.prevent="doClose('confirmContact')">&times;</button>
                </div>
                <div class="modal-body">
                    <p>You are about to remove {{isset($contact) ? $contact->fullname :'this contact'}} from this location.</p>
                   
                    
                      <div class="float-right">
                        <button class="btn btn-secondary"
                              type="button"
                              wire:click="doClose('confirmContact')">
                            Cancel
                          </button>
                        <button wire:click.defer="destroyContact('{{isset($contact) ? $contact->id :''}}')" class="btn btn-danger">Remove Contact</button>
                      
                      </div>
                   
                    
                </div>
                
            </div>
        </div>
    </div>