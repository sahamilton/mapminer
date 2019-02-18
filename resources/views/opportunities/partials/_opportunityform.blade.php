<div class="col-sm-6">
  <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Title</label>
    <div class="input-group input-group-lg ">
      <input type="text" 
        required 
        class="form-control" 
        name='title' 
        required
        description="title" 
        value="{{ old('title' , isset($opportunity)  ? $opportunity->title : "" ) }}" 
        placeholder="Opportunity Title">
      <span class="help-block">
        <strong>{{ $errors->has('title') ? $errors->first('title') : ''}}</strong>
      </span>
    </div>
  </div>

  <div class="form-group">
    <label class="col-md-4 control-label">Estimated Headcount:</label>
   
      <input class="form-control" 
      type="number" 
      min="0" step="1" 
      name="requirements" 
      value="{{ old('requirements' , isset($opportunity) ? $opportunity->requirements : '0' ) }}" />

  </div>
  <div class="form-group">
    <label class="col-md-4 control-label">Estimated Duration: (months):</label>
   
      <input class="form-control" 
      type="number" 
      min="0" step="1" 
      name="duration"
      value="{{ old('duration' , isset($opportunity) ? $opportunity->duration : '0' ) }}"  />

  </div>
  <div class="form-group">
    <label class="col-md-4 control-label">Estimated Revenue:</label>
    
      <input class="form-control" 
      type="number" 
      min="0" 
      step="1" 
      name="value" 
      value="{{ old('value' , isset($opportunity) ? $opportunity->value : '0' ) }}"  />
   
  </div>
  <div class="form-group{{ $errors->has('expected_close)') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="expected_close">Expected Close Date</label>
                
                <input class="form-control expected_close" 
                    type="text" 
                    required
                    name="expected_close" 
                    autocomplete='off' 
                    id="fromdatepicker" 
                    value="{{  old('expected_close', isset($opportunity) && $opportunity->expected_close ? $opportunity->expected_close->format('m/d/Y') : "" )}}"/>
                <span class="help-block">
                    <strong>{{$errors->has('expected_close') ? $errors->first('expected_close')  : ''}}</strong>
                </span>
                
            </div>

  <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Description</label>
    
    <textarea required 
    class="form-control" name='description' 
    title="opportunity description"
    placeholder="Describe the opportunity">{{ old('description' , isset($opportunity) ? $opportunity->description : '' ) }}</textarea>

    <span class="help-block">
    <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
    </span>
  </div>
</div>
        