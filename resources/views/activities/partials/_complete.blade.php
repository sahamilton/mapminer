<style>

.activity_date, .followup_date{z-index:1151 !important;}
</style>

<!-- Modal -->
<div class="modal fade" 
      id="complete-activity" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Complete Activity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form method="post" action="{{route('activity.complete')}}">
        @csrf
        <div class="form-group {{ $errors->has('note') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label">Notes:</label>
              <div class="input-group input-group-lg">
                  <textarea 
                  required 
                  class="form-control" 
                  name='note' 
                  title="note" 
                  value="">{{ old('note', isset($activity) ? $activity->note : '') }}</textarea>
                
                      <span class="help-block">
                      <strong>{{$errors->has('note') ? $errors->first('note')  : ''}}</strong>
                      </span>
      
              </div>
          </div>
          <div class="float-right">
            <button 
               type="button" 
               class="btn btn-default" 
               data-dismiss="modal">
             Cancel
            </button> 
            <input 
               type="submit" 
               value="Mark Activity as Completed" 
               class="btn btn-danger" />
            
          </div>
            <input 
              type="hidden" 
              name = "activity_id" 
              value="{{$activity->id}}" />
          </form>
        <div class="modal-footer">
      </div>
    </div>

      
    </div>

  </div>
</div>