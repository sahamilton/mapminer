@php $activities = [
  'Call',
  'Email',
  'Cold Call',
  'Sales Appointment',
  'Stop By',
  'Left material',
  'Proposal']; @endphp
  <style>
body.modal-open .activitydate, .followupdate {
    z-index: 1200 !important;
}
</style>
<!-- Modal -->
<div class="modal fade" 
      id="add_activity" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Record Lead Activity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form method="post" action="{{route('myleadsactivity.store')}}">
        {{csrf_field()}}
        <div class="form-group">
                    <label class="col-md-4 control-label">Activity:</label>
                    
                    <select  id="activity" required>
                      @foreach ($activities as $activity)
                        <option value="{{$activity}}">{{$activity}}</option>
                      @endforeach
        </select>
        </div>

         <div class="form-group {{ $errors->has('note') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label">Comments</label>
              <div class="input-group input-group-lg">
                  <textarea 
                  required 
                  class="form-control" 
                  name='note' 
                  title="note" 
                  value="{{ old('note') }}"></textarea>
                
                      <span class="help-block">
                      <strong>{{$errors->has('note') ? $errors->first('note')  : ''}}</strong>
                      </span>
      
              </div>
          </div>
          <div class="form-group{{ $errors->has('activitydate)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label" for="activitydate">Activity Date</label>
              <div class="input-group input-group-lg">
              <input class="form-control activitydate" 
                  type="text" 
                  name="activitydate"  
                  id="activitydate" 
                  value="{{  old('activitydate', date('m/d/Y')) }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('activitydate') ? $errors->first('activitydate')  : ''}}</strong>
              </span>
              </div>
          </div>
          <div class="form-group{{ $errors->has('followupdate)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label" for="followupdate">Followup Date</label>
              <div class="input-group input-group-lg">
              <input class="form-control followupdate" 
                  type="text" 
                  name="followupdate"  
                  id="followupdate" 
                  value="{{  old('followupdate', \Carbon\Carbon::now()->addWeek(1)->format('m/d/Y')) }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('activitydate') ? $errors->first('activitydate')  : ''}}</strong>
              </span>
              </div>
          </div>
          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Record Activity" class="btn btn-danger" />
            </div>
            <input type="hidden" name="lead_id" value="{{$mylead->id}}" />
        </form><div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>