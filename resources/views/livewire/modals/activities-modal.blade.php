<div>
    <style >[x-cloak] { display: none !important; }</style>
    <x-modal wire:model="show">
        
      <!-- Modal content-->
        
            <div class="modal-header">

                <h4 class="modal-title">Record Activity at {{$address ? $address->businessname : ''}} </h4>
                <button type="button" 
                    class="close" 
                    x-on:click="show = false" >&times;
                </button>
            </div>
            <div class="modal-body">
                
                    <x-form-select 
                        required 
                        wire:model.defer="activitytype_id" 
                        name="activitytype_id" 
                        label="Activity:" 
                        :options="$activityTypes" 
                    />
                    <x-form-textarea required wire:model.defer="note" name="note" label="Comments:" />
                    <x-form-input required type="date" wire:model.defer="activity_date" name="activity_date" label="Activity Date:" />
                    <x-form-checkbox checked wire:model.defer="completed" name="completed"  label="Completed:" />       
                    <x-form-input type="date" wire:model.defer="followup_date" name="followup_date" label="Followup Date:" />    
                    <x-form-select wire:model.defer="followup_activity" name="followup_activity" label="Followup Activity:" :options="$activityTypes" />
                    <input type="hidden" class="form-control" wire:model="address_id" id="address_id" name="address_id" value=""/>
                    


                    <div class="float-right">
                        <button class="btn btn-secondary"
                            type="button"
                            x-on:click="show = false">
                            Cancel
                        </button>
                        <button wire:click.ignore="store" class="btn btn-danger">Record Activity</button>

                    </div>

               
            </div>


     
    </x-modal>
</div>
