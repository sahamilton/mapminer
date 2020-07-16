<div class="modal fade" 
    id="addtocampaign" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="myModalLabel" 
    aria-hidden="true">
    <div class="modal-dialog"  
        role="document">
        <div class="modal-content">
        
            <div class="modal-header">
                
                <h4 class="modal-title" 
                id="myModalLabel">Add Lead to Campaign </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <p>Choose campaigns to add this lead to. </p>
                
                <form id="campaign-form" 
                        action="{{route('branchcampaign.add')}}" 
                        method="post"
                        >
                        <div class="form-group">
                            <label class="form-control"
                                for="campaign">Current Campaigns:</label>
                            <select
                            class="form-control"
                            name="campaign[]"
                            multiple
                            required >
                            @foreach ($campaigns as $campaign)
                            <option value="{{$campaign->id}}">{{$campaign->title}}</option>
                            @endforeach
                            </select>
                     
                        <input type="hidden" 
                            id="address_id" 
                            name="address_id" 
                            value="{{$location->id}}" />
                        
                        {{ csrf_field() }}
                        <input type="submit"name="submit" class="btn btn-info" />
                    </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                
                                    
                    
            </div>
        </div>
    </div>
</div>