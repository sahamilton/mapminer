<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<?php $rank = ($project->owner[0]->pivot->ranking ? $project->owner[0]->pivot->ranking: 3);?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Close {!!$project->project_title!!} Project  </h4>
      </div>
      <div class="modal-body">
        <p>Please complete this form to close project</p>
        <?php $ratings = [1,2,3,4,5];?>
        <form method="post" action="{{route('projects.close',$project->id)}}">
        {{csrf_field()}}
<div class="form-group">
            <label class="col-md-4 control-label">Project Rating:</label>
            <div style="font-size:150%" data-rating="{{$rank}}" id="rank" class='starrr col-md-6'></div>
            <input type="hidden" name="ranking" id="ranking" value="{{$rank}}" />
            
            <select  id="ranklist">
              @foreach ($statuses as $key=>$value)
              <option 
              @if($key == $rank) selected @endif value="{{$key}}">{{$value}}</option>
              @endforeach
</select>
</div>

         <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Comments</label>
                <div class="col-md-6">
                    <textarea required class="form-control" name='comments' title="comments" value="{{ old('comments') }}"></textarea>
                  
                        <span class="help-block">
                        <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
                        </span>
        
                </div>
            </div>
            <div class="pull-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Close Project" class="btn btn-danger" />
            </div>
            <input type="hidden" name="project_id" value="{{$project->id}}" />
        </form><div class="modal-footer">
        
        
      </div>
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
