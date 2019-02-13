@php
$types = $types = \App\FeedbackCategory::orderBy('category')->get();
@endphp

<div class="form-group">
    <label class="col-md-4 control-label">Feedback Type:</label>
    
    <select  id="activity" name="activity" required>
      @foreach ($types as $type)
        <option value="{{$type->id}}">{{$type->category}}</option>
      @endforeach
  </select>
</div>

<div class="form-group {{ $errors->has('feedback') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Comments</label>
    <div class="input-group input-group-lg">
        <textarea 
        required 
        class="form-control" 
        name='feedback' 
        title="feedback" 
        value="">{{ old('feedback', isset($feedback) ? $feedback->feedback : '') }}</textarea>
      
            <span class="help-block">
            <strong>{{$errors->has('feedback') ? $errors->first('feedback')  : ''}}</strong>
            </span>

    </div>
</div>
