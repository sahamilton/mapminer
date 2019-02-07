<div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Subject:</label>
	<div class="input-group input-group-lg">
		<input
		required 
		
		type="text" class="form-control" 
		name='subject' 
		description="subject" 
		value="{{ old('subject', isset($comment) ? $comment->subject :'' ) }}" 
		placeholder="subject">
		<span class="help-block">
			<strong>{{ $errors->has('subject') ? $errors->first('subject') : ''}}</strong>
		</span>
	</div>
</div>
<div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label">Feedback:</label>
	<textarea 
	name="comment" 
	required 
	class="form-control" 
	id="feedback" 
	rows="7">{{ old('comment', isset($comment) ? $comment->comment :'' ) }}</textarea>
		
		<span class="help-block">
			<strong>{{ $errors->has('comment') ? $errors->first('comment') : ''}}</strong>
		</span>
	
</div>
