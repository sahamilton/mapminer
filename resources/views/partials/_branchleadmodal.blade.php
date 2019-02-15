<div class="modal fade" id="confirm-remove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
            
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <div class="modal-body">
                    <p>You are about to remove this lead from your leads list. </p>
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
                            <input type="hidden" name="branch_id" value="{{$data['branches']->first()->id}}" />
                            
                            {{ csrf_field() }}
                        </form>
                </div>
            </div>
        </div>
    </div>