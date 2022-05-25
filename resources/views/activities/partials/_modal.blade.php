
   
    <div class="modal fade @if($show) show @endif"
         id="add_activity"

         style="display: @if($show === true)
                 block
         @else
                 none
         @endif;"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Activity at <span id="title"> {{isset($address) ? $address->businessname :'company'}} </span> </h5>
                    @if($errors->any())
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    @endif
                    <button type="button" class="close"  wire:click.prevent="doClose">&times;</button>
                </div>
                <div class="modal-body">
                    @wire('defer')
                    <x-form-select 
                        required 
                        
                        name="activitytype_id" 
                        label="Activity:" 
                        :options="$activityTypes" 
                    />
                    <x-form-textarea required name="note" label="Comments:" placeholder="Enter details...." />
                    <x-form-input required type="date"  id="activity_date" name="activity_date" label="Activity Date:" />
                    <x-form-checkbox checked  name="completed"  label="Completed:" />       
                    <x-form-input type="date" wire:model="followup_date" name="followup_date" label="Followup Date:"  />
                    @if($followup_date)   
                        <x-form-select  name="followup_activity" label="Followup Activity:" :options="$activityTypes" placeholder="Enter details...."/>
                    @endif
                    


                    <div class="float-right">
                        <button class="btn btn-secondary"
                            type="button"
                            wire:click.prevent="doClose">
                            Cancel
                        </button>
                        <button wire:click.ignore="store" class="btn btn-danger">Record Activity</button>

                    </div>
                    @endwire
                </div>
                
            </div>
        </div>
    </div>
    