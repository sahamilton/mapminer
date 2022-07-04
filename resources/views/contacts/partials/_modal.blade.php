
   
    <div class="modal fade @if($contactModalShow) show @endif"
         id="contactModalShow"

         style="display: @if($contactModalShow === true)
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
                    <h5 class="modal-title">Record Contact <span id="title"> {{isset($address) ? ' at ' .  $address->businessname :''}} </span> </h5>
                    
                    <button type="button" class="close"  wire:click.prevent="doClose('contactModalShow')">&times;</button>
                </div>
                <div class="modal-body">
                    @wire()
                    
                      
                        <x-form-input required name="contact.fullname" label="Full Name:" />
                        <x-form-checkbox name="contact.primary" label="Primary Contact:" />
                        <x-form-input required name="contact.title"  placeholder="Contact title"   label="Title:" />
                        <x-form-input name="contact.email"  placeholder="Contact email"   label="Email:" />
                        <x-form-input name="contact.contactphone"  placeholder="Contact phone"   label="Phone:" />      
                        <x-form-textarea name="contact.comments" label="Comments:" />
                        <div class="float-right">
                            <button class="btn btn-secondary"
                                type="button"
                                wire:click.prevent="doClose('contactModalShow')">
                                Cancel
                            </button>
                            <button wire:click.ignore="storeContact()" class="btn btn-danger">Record Contact</button>

                        </div>
                    @endwire
                </div>
                
            </div>
        </div>
    </div>
    