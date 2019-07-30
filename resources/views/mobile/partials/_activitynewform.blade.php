<div class="form-group">
        <label class="col-md-4 control-label">Activity:</label>
        
        <select  id="activitytype_id" name="activitytype_id" required>
          @foreach ($activities as $key=>$type)
            <option value="{{$key}}">{{$type}}</option>
          @endforeach
        </select>
        </div>
    @if(isset($address) && $address->contacts && $address->contacts->count()>0)
          
          <div class="form-group{{ $errors->has('activity_date)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label align-top" for="activity_date">Contact</label>
              <select name="contact" class form-control>
                <option></option>
              @foreach($address->contacts as $contact)
                <option value="{{$contact->id}}">{{$contact->fullname}}</option> 
              @endforeach
              </select>
              <span class="help-block">
                  <strong>{{$errors->has('contact') ? $errors->first('contat')  : ''}}</strong>
              </span>
             
          </div>
          @endif
         <div class="form-group {{ $errors->has('note') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label">Comments:</label>
              <div class="input-group input-group-lg">
                  <textarea 
                  required 
                  class="form-control" 
                  name='note' 
                  title="note" 
                  value="">{{ old('note') }}</textarea>
                
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
                  value="{{  old('activity_date', date('m/d/Y')) }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('activity_date') ? $errors->first('activity_date')  : ''}}</strong>
              </span>
              
          </div>
                  

          <div class="form-group{{ $errors->has('followup_date)') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label" for="followup_date">Followup Date</label>
              
              <input class="form-control followup_date" 
                  type="text" 
                  name="followup_date"  
                  id="followupdate"  
                  autocomplete="off"
                  value="{{  old('followup_date') }}"/>
              <span class="help-block">
                  <strong>{{$errors->has('followup_date') ? $errors->first('followup_date')  : ''}}</strong>
              </span>
             
          </div>
          <div class="form-group">
              <label class="col-md-4 control-label">Follow Up Activity:</label>
              
              <select  id="followup_activity" name="followup_activity">
                <option value="">No follow up</option>
                @foreach ($activities as $key=>$type)
                  <option value="{{$key}}">{{$type}}</option>
                @endforeach
        </select>
        </div>
         