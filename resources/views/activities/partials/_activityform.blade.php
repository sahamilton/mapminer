<div class="form-group">
                    <label class="col-md-4 control-label">Activity:</label>
                    
                    <select  id="activitytype_id" name="activitytype_id" required>
                      @foreach ($activities as $key=>$type)
                        <option value="{{$key}}">{{$type}}</option>
                      @endforeach
        </select>
        </div>
@if($location && $location->contacts && $location->contacts->count()>0)
          
          <div class="form-group{{ $errors->has('activity_date)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label align-top" for="activity_date">Contact</label>
              <select name="contact" class form-control>
                <option></option>
              @foreach($location->contacts as $contact)
                <option value="{{$contact->id}}">{{$contact->fullname}}</option> 
              @endforeach
              </select>
              <span class="help-block">
                  <strong>{{$errors->has('contact') ? $errors->first('contat')  : ''}}</strong>
              </span>
             
          </div>
          @endif
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
          <div class="form-group{{ $errors->has('activity_date)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label" for="activity_date">Activity Date</label>
              
              <input class="form-control activity_date" 
                  type="text" 
                  name="activity_date" 
                  autocomplete='off' 
                  id="activitydate" 
                  value="{{  old('activity_date', isset($activity) ? $activity->activity_date->format('m/d/Y') : date('m/d/Y')) }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('activity_date') ? $errors->first('activity_date')  : ''}}</strong>
              </span>
              
          </div>
          <div class="form-group inline">
            <label class="col-md-4 control-label" for="completed">Completed</label>
            <input class="form-control"
            type="checkbox"
            name="completed"
            checked
            />
          </div>
          

          <div class="form-group{{ $errors->has('followup_date') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label" for="followup_date">Followup Date</label>
              
              <input class="form-control followup_date" 
                  type="text" 
                  name="followup_date"  
                  id="followupdate"  
                  autocomplete="off"
                  value="{{  old('followup_date', isset($activity) && isset($activity->followup_date) ? $activity->followup_date->format('m/d/Y') : '') }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('followup_date') ? $errors->first('followup_date')  : ''}}</strong>
              </span>
             
          </div>
          <div class="form-group">
              <label class="col-md-4 control-label">Follow Up Activity:</label>
              
              <select  id="followup_activity" name="followup_activity">
                @foreach ($activities as $key=>$type)
                  <option value="{{$key}}">{{$type}}</option>
                @endforeach
        </select>
        <input type="hidden" name="branch_id" value="{{$activity ? $activity->branch_id : ''}}" />
        </div>
         