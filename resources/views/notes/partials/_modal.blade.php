<div class="modal fade @if($noteModalForm) show @endif"
     id="noteModalForm"

     style="overflow-y: auto;display: @if($noteModalForm === true)
             block
     @else
             none
     @endif;"

     tabindex="-1"
     role="dialog"
     aria-labelledby="modal-title"
     aria-hidden="true">
    <div class="modal-dialog" 
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$title}}  for {{$address->businessname }}</h5>
                
                <button type="button" class="close"  wire:click.prevent="doClose('noteModalForm')">&times;</button>
            </div>
            <div class="modal-body">
                @wire()
                    @if($errors->any())
                        <span class="text-danger">Check your inputs</span>
                    @endif


                    <x-form-textarea wire:defer required name="note.note" label="Comments:" placeholder="Enter notes...." />

                    

                    <div class="float-right">
                        <button class="btn btn-secondary"
                            type="button"
                            wire:click.prevent="doClose('noteModalForm')">
                            Cancel
                        </button>
                        <button wire:click.ignore="storeNote()" class="btn btn-danger"> {{$title}} </button>

                    </div>
                @endwire
            </div>
        </div>
    </div>
</div>