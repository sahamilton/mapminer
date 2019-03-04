<!-- Activity-->
<div class="form-group{{ $errors->has('activity)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="activity">Activity</label>
    <input class="form-control activity" 
        type="text" 
        name="activity" 
        required
        id="activitydate" 
        value="{{  old('activity', isset($activitytype) ? $activitytype->activity : '') }}"/>
    <span class="help-block">
        <strong>{{$errors->has('activity') ? $errors->first('activity')  : ''}}</strong>
    </span>
    
</div>
