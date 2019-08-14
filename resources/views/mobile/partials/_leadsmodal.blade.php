<div class="modal fade" 
    id="add-lead" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="myModalLabel" 
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Lead Details</h4>
            </div>
        
            <div class="modal-body">
                <form id="action-form" 
                    name="action-form" 
                    method="post" 
                    action="{{route('myleads.store')}}"
                    >
                @csrf
			    @include('mobile.partials._lead2form')

                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="hidden" name="source" value="mobile" />
                <input type="hidden" name="branch" value="{{$branch->id}}" />
                <input type="submit" 
                name="submit" 
                class="btn btn-warning warning" value="Add Lead" />
            </form>
            </div>
            
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>