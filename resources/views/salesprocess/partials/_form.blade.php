<div class="form-group{{ $errors->has('step') ? ' has-error' : '' }}">
<label for="step">Step</label>
<input type="text" required class='form-control' name="step" value="{{isset($process->step) ? $process->step :'' }}" />
{!! $errors->first('step', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('sequence') ? ' has-error' : '' }}">
<label for="step">Sequence</label>
<input type="text" required class='form-control' name="sequence" value="{{isset($process->sequence) ?  $process->sequence : '' }}" />
{!! $errors->first('sequence', '<p class="help-block">:message</p>') !!}
</div>
   