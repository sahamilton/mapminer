<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Release</h4>
                </div>
            
                <div class="modal-body">
                    <p>You are about to release <span id='title'>this project</span>.  Note this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger danger"
                        onclick="event.preventDefault();
                        document.getElementById('action-form').submit();"
                        class="btn btn-danger danger">Release</a>
                                        
                        <form id="action-form" 
                            action="" 
                            method="post" 
                            style="display: none;">
                            
                            {{ csrf_field() }}
                        </form>
                </div>
            </div>
        </div>
    </div>