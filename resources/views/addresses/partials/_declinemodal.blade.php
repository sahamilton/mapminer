<!-- Modal -->
@php
  $rank =  3 ;
  $ratings = [1,2,3,4,5];
@endphp
<div id="decline-lead" 
  class="modal fade" 
  role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Decline {!!$location->businessname !!} lead  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Please complete this form to rate this locations data</p>
       
        <form 
          name="action-form"
          method="post" 
          action="{{route('branchleads.destroy',$branch->pivot->id)}}">

          @method('delete')
          @csrf


          <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
            <label for="comment">Reason to Decline</label>
            
            <textarea required 
            class="form-control" name='comment' title="comment" value=""></textarea>

            <span class="help-block">
            <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
            </span>
         
          </div>
        
          <div class="float-right">
            <input type="submit" value="Reject lead" class="btn btn-danger" />
          </div>
          <input type="hidden" name="address_id" value="{{$location->id}}" />
        </form>
      </div>
      <div class="modal-footer">
     </div>
    </div>
  </div>
</div>

<script>
  $('#rank').on('starrr:change', function(e, value){

    $("#ranking").val(value),
    $('#ranklist').val(value);
  });
  $('#ranklist').change (function(){

    $("#rank").val(this.value),
    $('#ranking').val(this.value);
  });

</script>
