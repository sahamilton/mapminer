<div class="form-group{{ $errors->has('quote') ? ' has-error' : '' }}">
<label for="quote">Quote</label>
<textarea class='form-control' name="quote">{!! $quote->quote !!}</textarea>
{!! $errors->first('quote', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('attribution') ? ' has-error' : '' }}">
        <label for= "attribution">Attribution</label> 
        <input class="form-control" name="attribution" value="{{$quote->attribution}}" />
        {!! $errors->first('attribution', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('source1') ? ' has-error' : '' }}">
        <label for= "source1">Source</label> 
        <input class="form-control" name="source1" value="{{$quote->source1}}" />
        {!! $errors->first('source1', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('source2') ? ' has-error' : '' }}">
        <label for= "source2">Act</label> 
        <input class="form-control" name="source2" value="{{$quote->source2}}" />
        {!! $errors->first('source2', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group{{ $errors->has('source3') ? ' has-error' : '' }}">
        <label for= "source3">Scene</label> 
        <input class="form-control" name="source3" value="{{$quote->source3}}" />
        {!! $errors->first('source3', '<p class="help-block">:message</p>') !!}
    </div>