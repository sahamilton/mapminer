
   
    <div class="modal fade @if($opportunityModal) show @endif"
         id="opportunityModal"

         style="display: @if($opportunityModal === true)
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
                    
                    <button type="button" class="close"  wire:click.prevent="doClose('opportunityModal')">&times;</button>
                </div>
                <div class="modal-body">
                    
                        @wire
                        <x-form-input       class="col-sm-10" required type="text"      name="opportunity.title" label="Title:" />
                        <x-form-input       class="col-sm-10" required type="number"    name="opportunity.requirements"  step=1 min=0 label="Requirements (headcount):"/> 
                        <x-form-input       class="col-sm-10" required type="number"    name="opportunity.duration"  step=1 min=0 label="Duration (months):"/> 
                        <x-form-input       class="col-sm-10" required type="number"    name="opportunity.value"  min=0 label="Value: (in whole $'s)"/> 
                        <x-form-input       class="col-sm-10" required type="date"      name="opportunity.expected_close" label="Expected Close Date:" />     
                        <x-form-textarea    class="col-sm-10" required                  name="opportunity.description"  label="Description:" placeholder="Enter details...." />
                        
                        <x-form-group class="p-2"  inline>
                            <x-form-checkbox class="float-left" name="opportunity.Top25" value='1' label="Top 25 Opportunity?" />
                            <x-form-checkbox class="float-right" name="opportunity.csp"  value='1' label="CSP Opportunity?" />
                        </x-form-group>

                        <div class="float-right">
                            <button class="btn btn-secondary"
                                type="button"
                                wire:click.prevent="doClose('opportunityModal')">
                                Cancel
                            </button>
                            <button wire:click.ignore="storeOpportunity" class="btn btn-danger">Record Opportunity</button>

                        </div>
                        @endwire
                    
                </div>
                
            </div>
        </div>
    </div>
    