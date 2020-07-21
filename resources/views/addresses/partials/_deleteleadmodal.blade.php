<!-- Modal -->

<div id="delete-lead" 
  class="modal fade" 
  role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Delete Lead  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Please state the reason for deleting <span id="title">this lead</span>.</p>
       
        <form 
          id="action-form"
          name="action-form"
          method="post" 
          action="">

          @method('delete')
          @csrf


          <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
            <label for="comment">Reason to Delete</label>
            
            <textarea required 
            class="form-control" name='comment' title="comment" value=""></textarea>

            <span class="help-block">
            <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
            </span>
         
          </div>
        
          <div class="float-right">
            <input type="submit" value="Delete lead" class="btn btn-danger" />
          </div>
          <input type="hidden" name="branch_id" value="{{$branch->id}}" />
        </form>
      </div>
      <div class="modal-footer">
     </div>
    </div>
  </div>
</div>

