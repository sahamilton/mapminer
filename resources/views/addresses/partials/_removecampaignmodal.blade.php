<div class="modal fade" 
id="confirm-remove-campaign" 
tabindex="-1" 
role="dialog" 
aria-labelledby="myModalLabel" 
aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
            
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="deletecampaignLabel">Confirm Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <div class="modal-body">
                    <p>You are about to <span id='campaigndeletetitle'>this item</span>. </p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger danger"
                        onclick="event.preventDefault();
                        document.getElementById('campaign-remove-action-form').submit();"
                        class="btn btn-danger danger">Delete</a>           
                        <form id="campaign-remove-action-form" 
                            action="{{route('branchcampaign.delete')}}" 
                            method="post" 
                            style="display: none;">
                            @method('post')
                            @csrf
                            <input type="hidden"
                            id="campaign_id"
                            name="campaign_id"
                            value="">
                            <input type="hidden" 
                            id="address_id" 
                            name="address_id" 
                            value="{{$location->id}}" />
                        </form>
                </div>
            </div>
        </div>
    </div>