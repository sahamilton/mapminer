<div class="modal fade" id="confirm-opportunitydelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
            
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <div class="modal-body">
                    <p>You are about delete <span id='title'>this item</span>.  Note this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger danger"
                        onclick="event.preventDefault();
                        document.getElementById('action-form').submit();"
                        class="btn btn-danger danger">Delete</a>
                                        
                        <form id="action-form" 
                            action="" 
                            method="post" 
                            style="display: none;">
                            @method('delete')
                            @csrf
                        </form>
                </div>
            </div>
        </div>
    </div>