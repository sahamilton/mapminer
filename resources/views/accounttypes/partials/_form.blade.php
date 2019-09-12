<div class="form-group{{ $errors->has('type)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="type">Account Type</label>
    <input class="form-control type" 
        type="text" 
        name="type" 
        required
        id="type" 
        value="{{  old('type', isset($accounttype)? $accounttype->type : '') }}"/>
    <span class="help-block">
        <strong>{{$errors->has('type') ? $errors->first('type')  : ''}}</strong>
    </span>
    
</div>