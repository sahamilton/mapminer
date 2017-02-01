<div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
<label for="user">USer</label>
<textarea class='form-control' name="user">{!! $user->user !!}</textarea>
{!! $errors->first('user', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('attribution') ? ' has-error' : '' }}">
        <label for= "attribution">Attribution</label> 
        <input class="form-control" name="attribution" value="{{$user->attribution}}" />
        {!! $errors->first('attribution', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('source1') ? ' has-error' : '' }}">
        <label for= "source1">Source</label> 
        <input class="form-control" name="source1" value="{{$user->source1}}" />
        {!! $errors->first('source1', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('source2') ? ' has-error' : '' }}">
        <label for= "source2">Act</label> 
        <input class="form-control" name="source2" value="{{$user->source2}}" />
        {!! $errors->first('source2', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('source3') ? ' has-error' : '' }}">
        <label for= "source3">Scene</label> 
        <input class="form-control" name="source3" value="{{$user->source3}}" />
        {!! $errors->first('source3', '<p class="help-block">:message</p>') !!}
    </div>