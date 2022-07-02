        <div class="form-group {{ $errors->has('contact') ? ' has-error' : '' }}">
          
          <x-form-input required name="fullname" placeholder ="Name" label="Full Name:" />
        </div>

        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
          <x-form-input required name="title"  placeholder="Contact title"   label="Title:" />
                
        </div>

        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
          <x-form-input type="email" name="email"  placeholder="Contact email"   label="Email:" />
                
        </div>

        <div class="form-group {{ $errors->has('contactphone') ? ' has-error' : '' }}">
          <x-form-input  name="contactphone"  placeholder="Contact phone"   label="Phone:" />      
        </div>


         <div class="form-group {{ $errors->has('comments') ? ' has-error' : '' }}">
              <x-form-textarea name="comments" label="Comments:" />
              
          </div>

          <div class="form-group {{ $errors->has('primary') ? ' has-error' : '' }}">
              <x-form-checkbox name="primary" label="Primary Contact:" />
              
          </div>
         