<!-- Documents Modal -->

<div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
               @include('campaigns.documents._form')
            </div>
            <div class="modal-footer">
                <button type="button" 
                    class="btn btn-secondary close-btn" 
                    data-dismiss="modal">Close</button>
                <button type="button" 
                    wire:click.prevent="saveDocument()" 
                    class="btn btn-primary close-modal"
                    data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
                