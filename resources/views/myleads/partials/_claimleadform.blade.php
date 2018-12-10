<div class="modal fade" id="claimlead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Prospect Details</h4><button type="button" class="close float-right" data-dismiss="modal" aria-hidden="true">&times;</button>
                    
                </div>
            
                <div class="modal-body">
				<legend>Contact Details</legend>
				<p>{{$lead->contact}}</p>
                <p>{{$lead->contacttitle}}</p>
                <p>{{$lead->contactemail}}</p>
				<p>{{$lead->phone}}
				<legend>Address</legend>
				<p>{{$lead->address}}</p>
				<p>{{$lead->city}} {{$lead->state}}</p>
				<p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                     <form method="post" action="{{route('lead.claim',$lead->id)}}">
                    <input type="submit" class="btn btn-warning warning" value="Accept Prospect" />
                </div>
            </div>
        </div>
    </div>