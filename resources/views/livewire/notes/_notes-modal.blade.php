<div>
    <style >[x-cloak] { display: none !important; }</style>
    <x-modal wire:model="noteModalForm">
        
      <!-- Modal content-->
        
            <div class="modal-header">

                <h4 class="modal-title">Record Note for {{$address ? $address->businessname : ''}} </h4>
                <button type="button" 
                    class="close" 
                    wire:click="doClose('noteModalForm')" >&times;
                </button>
            </div>
            <div class="modal-body">
                
               
                    <x-form-textarea required wire:model.defer="note.note" name="note.note" label="Note:" /><input type="hidden" class="form-control" wire:model="note.address_id" id="note.address_id" name="note.address_id" value=""/>
                    
                    <x-form-input hidden wire:model='note.address_id' name='note.address_id' label="Address" />

                    <div class="float-right">
                        <button class="btn btn-secondary"
                            type="button"
                            wire:click="doClose('noteModalForm')">
                            Cancel
                        </button>
                        <button wire:click.ignore="storeNote()" class="btn btn-danger">Record Note</button>

                    </div>
            </div>
     
    </x-modal>
</div>
