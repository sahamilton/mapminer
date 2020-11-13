        <div class="form-group {{ $errors->has('contact') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">Contact:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                  required
                  name="fullname"  
                  id="fullname" 
                  value="{{  old('contact', isset($contact) ? $contact->fullname : '') }}"
                  placeholder="contact name"/>
              <span class="help-block">
                  <strong>{{$errors->has('contact') ? $errors->first('contact')  : ''}}</strong>
              </span>
          </div>          
        </div>

        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">Contact Title:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                  required
                  name="title"  
                  id="title" 
                  value="{{  old('title', isset($contact) ? $contact->title : '') }}"
                  placeholder="contact title"/>
              <span class="help-block">
                  <strong>{{$errors->has('title') ? $errors->first('title')  : ''}}</strong>
              </span>
          </div>          
        </div>

        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">Contact Email:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                 
                  name="email"  
                  id="email" 
                  value="{{  old('email', isset($contact) ? $contact->email : '') }}"
                  placeholder="contact email"/>
              <span class="help-block">
                  <strong>{{$errors->has('email') ? $errors->first('email')  : ''}}</strong>
              </span>
          </div>          
        </div>

        <div class="form-group {{ $errors->has('contactphone') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">Contact Phone:</label>
          <div class="input-group input-group-lg">
            <input class="form-control" 
                  type="text" 
                  
                  name="contactphone"  
                  id="contactphone" 
                  value="{{  old('contactphone', isset($contact) ? $contact->contactphone : '') }}"
                  placeholder="contact phone"/>
              <span class="help-block">
                  <strong>{{$errors->has('phone') ? $errors->first('contactphone')  : ''}}</strong>
              </span>
          </div>          
        </div>


         <div class="form-group {{ $errors->has('comments') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label">Comments</label>
              <div class="input-group input-group-lg">
                  <textarea 
               
                  class="form-control" 
                  name='comments' 
                  title="comments" 
                  value="{{ old('comments', isset($contact) ? $contact->comments : '') }}"></textarea>
                
                      <span class="help-block">
                      <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
                      </span>
      
              </div>
          </div>

          <div class="form-group {{ $errors->has('primary') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label">Primary</label>
              <div class="input-group input-group-lg">
                  <input type="checkbox"
                    class="form-control" 
                    name='primary' 
                    title="primary"
                    @if(isset($contact) && $contact->primary ==1)
                    checked 
                    @endif 
                    value="1">
                
                      <span class="help-block">
                      <strong>{{$errors->has('primary') ? $errors->first('primary')  : ''}}</strong>
                      </span>
      
              </div>
          </div>
         