<!-- Modal -->

<div id="" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Rate {!!$location->businessname!!} data  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        
        <form method="post" action="{{route('lead.reassign',$location->id)}}">
          @csrf
          <div class="form-group">
            <label class="col-md-2 control-label">Transfer to:</label>
            <

              <select class="form-control input-sm" id="branch">
                @foreach ($mybranches as $key=>$value)
                  <option  value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
          </div>

          
          <div class="float-right">
            <input type="submit" value="Transfer" class="btn btn-success" />
          </div>
         
        </form>
      </div>
      <div class="modal-footer">
     </div>
    </div>
  </div>
</div>