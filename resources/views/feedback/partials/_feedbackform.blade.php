@php
$types = $types = \App\FeedbackCategory::orderBy('category')->get();
@endphp

<div class="form-group">
    <label class="col-md-4 control-label">Feedback Type:</label>
    
    <select  id="type" name="type" required>
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
@can('manage_feedback')
@php $ratings = [1,2,3,4,5]; @endphp
<div class="form-group {{ $errors->has('biz_rating') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Business Rating (5 = Highly important) </label>
    
         <select  id="biz_rating" name="biz_rating" required>
              @foreach ($ratings as $rate)
                <option value="{{$rate}}">{{$rate}}</option>
              @endforeach
        </select>
    
</div>

<div class="form-group {{ $errors->has('tech_rating') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Technical Rating (5 = Very Difficult) </label>
    
         <select  id="tech_rating" name="tech_rating" required>
              @foreach ($ratings as $rate)
                <option value="{{$rate}}">{{$rate}}</option>
              @endforeach
        </select>
   
</div>
@endcan
