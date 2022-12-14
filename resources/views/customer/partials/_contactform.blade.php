        <div class="form-group">
          <label class="col-md-4 control-label">Contact:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                  required
                  name="contact"  
                  id="contact" 
                  value="{{  old('contact') }}"
                  placeholder="contact name"/>
              <span class="help-block">
                  <strong>{{$errors->has('contact') ? $errors->first('contact')  : ''}}</strong>
              </span>
          </div>          
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label">Contact Title:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                  required
                  name="contacttitle"  
                  id="contacttitle" 
                  value="{{  old('contacttitle') }}"
                  placeholder="contact title"/>
              <span class="help-block">
                  <strong>{{$errors->has('contacttitle') ? $errors->first('contacttitle')  : ''}}</strong>
              </span>
          </div>          
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label">Contact Email:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                  required
                  name="contactemail"  
                  id="contactemail" 
                  value="{{  old('contactemail') }}"
                  placeholder="contact email"/>
              <span class="help-block">
                  <strong>{{$errors->has('contactemail') ? $errors->first('contactemail')  : ''}}</strong>
              </span>
          </div>          
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label">Contact Phone:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                  required
                  name="contactphone"  
                  id="contactphone" 
                  value="{{  old('contactphone') }}"
                  placeholder="contact phone"/>
              <span class="help-block">
                  <strong>{{$errors->has('contactphone') ? $errors->first('contactphone')  : ''}}</strong>
              </span>
          </div>          
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
          <input type="hidden" id="address_id" name="address_id" value="{{$location->id}}" />