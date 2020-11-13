<!-- Activity-->
<div class="form-group{{ $errors->has('activity)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="activity">Activity</label>
    <input class="form-control activity" 
        type="text" 
        name="activity" 
        required
        id="activity" 
        value="{{  old('activity', isset($activitytype) ? $activitytype->activity : '') }}"/>
    <span class="help-block">
        <strong>{{$errors->has('activity') ? $errors->first('activity')  : ''}}</strong>
    </span>
    
</div>
<div class= "form-group">
    <label class="col-md-4 control-label" for="color">Color</label>
    <input class="form-control colorpicker-component" 
        type="text" 
        name="color" 
        required
        id="color" 
        value="{{  old('color', isset($activitytype) ? $activitytype->color : '') }}"/>
    <span class="help-block">
        <strong>{{$errors->has('color') ? $errors->first('color')  : ''}}</strong>
    </span>
</div>
<div class= "form-group">
    <label class="col-md-4 control-label" for="defintion">Defintion</label>
    <textarea class="form-control" 
        name="definition"
        id="defintion">{{old('definition' , isset($activitytype) ? $activitytype->definition : '')}}</textarea>
    <span class="help-block">
        <strong>{{$errors->has('color') ? $errors->first('color')  : ''}}</strong>
    </span>
</div>