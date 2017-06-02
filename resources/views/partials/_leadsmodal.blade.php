<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Lead Details</h4>
                </div>
            
                <div class="modal-body">
				<legend>Contact Details</legend>
				<p>{{$lead->contact}}</p>
				<p>{{$lead->phone}}
				<legend>Address</legend>
				<p>{{$lead->address}}</p>
				<p>{{$lead->city}} {{$lead->state}}</p>
				<p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-warning warning">Accept Lead</a>
                </div>
            </div>
        </div>
    </div>