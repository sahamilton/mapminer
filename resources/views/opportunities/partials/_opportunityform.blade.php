
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
        value="{{ old('title' , isset($opportunity)  ? $opportunity->title : 'Opportunity @ ' . $location->businessname) }}" 
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
      required
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
      required
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
@php $statuses = [0=>'open',1=>'Closed-Won',2=>'Closed-Lost'] @endphp
<div class="form-group">
    <label class="col-md-4 control-label">Status:</label>
    
      <select class="form-control" 
       
      required
    
      name="closed" >
      @foreach ($statuses as $key=>$status)
        @if(isset($opportunity) and $opportunity->closed == $key)
            <option  selected  value={{$key}}>{{$status}}</option>
        @else
            <option  value={{$key}}>{{$status}}</option>
        @endif
      @endforeach
    </select>
      
   
  </div>
  @if(isset($opportunity))
 <div class="form-group{{ $errors->has('expected_close)') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="expected_close">Actual Close Date</label>
                
                <input class="form-control expected_close" 
                    type="text" 
                    
                    name="actual_close" 
                    autocomplete='off' 
                    id="todatepicker" 
                    value="{{  old('actual_close', isset($opportunity) && $opportunity->actual_close ? $opportunity->actual_close->format('m/d/Y') : "" )}}"/>
                <span class="help-block">
                    <strong>{{$errors->has('actual_close') ? $errors->first('actual_close')  : ''}}</strong>
                </span>
                
            </div>

  @endif

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

<!-- CSP  -->
<div class="form-group  {{ $errors->has('csp') ? ' has-error' : ''}}">
    <label class=" control-label">CSP Opportunity:
    <div class="input-group checkbox inline input-group-sm">
        <input 
        type="checkbox" 
        class="form-control" 
        name='csp' 
        @if(old('csp', isset($opportunity) && $opportunity->csp))
            checked
        @endif
        value="1"
        title="CSP Opportunity"

        ></label>
        <span class="help-block">
            <strong>{{ $errors->has('csp') ? $errors->first('csp') : ''}}</strong>
        </span>
    </div>
</div>
        