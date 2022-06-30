
   
    <div class="modal fade @if($opportunityModalShow) show @endif"
         id="add_activity"

         style="display: @if($opportunityModalShow === true)
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
                    <h5 class="modal-title">Record Opportunity at <span id="title"> {{isset($address) ? $address->businessname :'company'}} </span> </h5>
                    
                    <button type="button" class="close"  wire:click.prevent="doClose">&times;</button>
                </div>
                <div class="modal-body">
                    @wire('defer')
                    <x-form-input required type="text"  id="title" name="title" value="Opportunity @ {{$address->businessname}}" label="Title:" />
                    <x-form-input required type="number"  id="requirements" name="requirements" step=1 min=0 label="Requirements (headcount):"/> 
                    <x-form-input required type="number"  id="duration" name="duration" step=1 min=0 label="Duration (months):"/> 
                    <x-form-input required type="number"  id="value" name="value" min=0 label="Value:"/> 
                    <x-form-input required type="date"  id="expected_close" name="expected_close" label="Expected Close Date:" />     
                    <x-form-input type="date" wire:model="actual_close" name="actual_close" label="Actual Close Date:"  />
                    <x-form-textarea required name="description" label="Description:" placeholder="Enter details...." />
                    <x-form-checkbox name="Top25" value='1' label="Top 25 Opportunity?" />
                    <x-form-checkbox name="csp" value='1' label="CSP Opportunity?" />
                    


                    <div class="float-right">
                        <button class="btn btn-secondary"
                            type="button"
                            wire:click.prevent="doClose">
                            Cancel
                        </button>
                        <button wire:click.ignore="storeOpportunity" class="btn btn-danger">Record Opportunity</button>

                    </div>
                    @endwire
                </div>
                
            </div>
        </div>
    </div>
    