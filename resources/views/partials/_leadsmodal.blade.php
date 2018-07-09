<div class="modal fade" id="accept-lead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Prospect Details</h4>
                </div>
            
                <div class="modal-body">
				<legend>Contact Details</legend>
				<p>{{$lead->contact}}</p>
                <p>{{$lead->contacttitle}}</p>
                <p>{{$lead->contactemail}}</p>
				<p>{{$lead->phone}}
				<legend>Address</legend>
				<p>{{$lead->address->address}}</p>
				<p>{{$lead->address->city}} {{$lead->address->state}}</p>
				<p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-warning warning">Accept Prospect</a>
                </div>
            </div>
        </div>
    </div>