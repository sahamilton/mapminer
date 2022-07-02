
   
    <div class="modal fade @if($addressModal) show @endif"
         id="addressModal"

         style="display: @if($addressModal === true)
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
                    
                    <button type="button" class="close"  wire:click.prevent="doClose('addressModal')">&times;</button>
                </div>
                <div class="modal-body">
                    
                    @wire('defer')
                      <x-form-input required name="location.businessname" label="Business name:" />
                      <x-form-input required name="location.street" label="Address:" />
                      <x-form-input required name="location.city" label="City:" />
                      <x-form-select required name="location.state" label="State:" :options="$states" />
                      <x-form-input required name="location.zip" label="ZIP/Postcode:" />
                      <x-form-input name="location.phone" label="Phone:" />
                      <x-form-input name="location.customer_id" label="Customer ID:" />
                    
                      <div class="float-right">
                        <button class="btn btn-secondary"
                              type="button"
                              x-on:click="doClose('addressModal')">
                            Cancel
                          </button>
                        <button wire:click.defer="updateAddress()" class="btn btn-danger">Update Address</button>
                      
                      </div>
                    @endwire
                    
                </div>
                
            </div>
        </div>
    </div>