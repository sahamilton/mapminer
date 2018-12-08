        <div class="form-group">
                    <label class="col-md-4 control-label">Activity:</label>
                    
                    <select  id="activity" name="activity" required>
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
          <input type="hidden" name="lead_id" value="{{$mylead->id}}" />