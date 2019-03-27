<!-- Modal -->

<div id="reassign" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Transfer {!!$location->businessname!!}   </h4>
        <button type="button" 
        class="close" 
        data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        
        <form method="post" action="{{route('lead.reassign',$location->id)}}">
          @csrf
          @if(count($myBranches)>1)
          <div class="form-group">
            <label class="col-md-2 control-label">Transfer to:</label>
          

              <select class="form-control input-sm"  
              multiple  
              name="branch[]" 
              id="branch">
                @foreach ($myBranches as $key=>$value)
                  <option  value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
          </div>
          <p>Or enter branch ids separated by commas</p>
          @endif
          <div class="form-group">
            <label class="col-md-2 control-label">Transfer to:</label>
            <input type="text" 
            class="form-control" 
            name="branch_id" 
            placeholder ="Enter branch numbers separated by commas" />
          </div>
          <input type="hidden" 
          name="address_id" 
          value="{{$location->id}}" />
          
          <div class="float-right">
            <input type="submit" 
            value="Transfer" 
            class="btn btn-success" />
          </div>
         
        </form>
      </div>
      <div class="modal-footer">
     </div>
    </div>
  </div>
</div>