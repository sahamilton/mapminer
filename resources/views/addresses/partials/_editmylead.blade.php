<div class="modal fade" 
      id="edit_lead" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Edit Lead</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form name="edit" action="{{route('myleads.update',$location->id)}}" method="post">
        @csrf
        @method('put')
        @include('myleads.partials._form')
          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Edit Lead" class="btn btn-danger" />
            </div>
            <input type="hidden" name="address_id" value="{{$location->id}}" />
        </form><div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>