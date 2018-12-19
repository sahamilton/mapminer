        <div class="form-group">
                    <label class="col-md-4 control-label">Activity:</label>
                    
                    <select  id="activity" name="activity" required>
                      @foreach ($activities as $key=>$activity)
                        <option value="{{$key}}">{{$activity}}</option>
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
          <div class="form-group{{ $errors->has('activity_date)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label" for="activity_date">Activity Date</label>
              <div class="input-group input-group-lg">
              <input class="form-control activity_date" 
                  type="text" 
                  name="activity_date"  
                  id="fromdatepicker" 
                  value="{{  old('activity_date', date('m/d/Y')) }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('activity_date') ? $errors->first('activity_date')  : ''}}</strong>
              </span>
              </div>
          </div>
          <div class="form-group{{ $errors->has('followup_date)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label" for="followup_date">Followup Date</label>
              <div class="input-group input-group-lg">
              <input class="form-control followup_date" 
                  type="text" 
                  name="followup_date"  
                  id="todatepicker"  
                  value="{{  old('followup_date') }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('activitydate') ? $errors->first('activitydate')  : ''}}</strong>
              </span>
              </div>
          </div>
         