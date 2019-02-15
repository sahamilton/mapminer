<!--- Title -->

    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Title</label>
              <div class="input-group input-group-lg ">
            <input type="text" 
            required 
            class="form-control" 
            name='title' 
            required
            description="title" 
            value="{{ old('title' , isset($opportunity) && $opportunity->title ? $opportunity->title : $opportunity->id ) }}" 
            placeholder="title">
            <span class="help-block">
                <strong>{{ $errors->has('title') ? $errors->first('title') : ''}}</strong>
                </span>
        </div>
    </div>
<!--- value -->

    <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Value</label>
           <div class="input-group input-group-lg ">
            <input type="text" 
            required 
            class="form-control" 
            name='value' 
            description="value" 
            value="{{ old('value' , isset($opportunity) ? $opportunity->value : "0" ) }}" 
            placeholder="value">
            <span class="help-block">
                <strong>{{ $errors->has('value') ? $errors->first('value') : ''}}</strong>
                </span>
        </div>
    </div>
 <!--- requirements -->

    <div class="form-group{{ $errors->has('requirements') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Requirements</label>
              <div class="input-group input-group-lg ">
            <input type="text" 
            required 
            class="form-control" 
            name='requirements' 
            description="requirements" 
            value="{{ old('requirements' , isset($opportunity) ? $opportunity->requirements : '0' ) }}" 
            placeholder="requirements">
            <span class="help-block">
                <strong>{{ $errors->has('requirements') ? $errors->first('requirements') : ''}}</strong>
                </span>
        </div>
    </div>
  <!--- duration -->

    <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Duration</label>
              <div class="input-group input-group-lg ">
            <input type="text" 
            required 
            class="form-control" 
            name='duration' 
            description="duration" 
            value="{{ old('duration' , isset($opportunity) ? $opportunity->duration : "0" ) }}" 
            placeholder="duration">
            <span class="help-block">
                <strong>{{ $errors->has('duration') ? $errors->first('duration') : ''}}</strong>
                </span>
        </div>
    </div>

