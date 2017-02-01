<div>
{{Form::label('subject','Category:')}}
<div>
{{Form::select('subject', array('Usability' => 'Usability', 'Feature Request' => 'Feature Request','Bug' => 'Bug', 'Other' => 'Other'))}}
{{ $errors->first('subject') }}
</div></div>


<div>
{{Form::label('title','Title:')}}
<div>
{{Form::text('title')}}
{{ $errors->first('title') }}
</div></div>


<div>
{{Form::label('comment','Feedback:')}}
<div>
{{Form::textarea('comment')}}
{{ $errors->first('comment') }}
</div></div>

<div>
{{Form::label('status','Status:')}}
<div>
{{Form::select('comment_status',array('open'=>'open','closed'=>'closed'))}}
{{ $errors->first('comment_status') }}
</div></div>

<!-- Form Actions -->
	<div style="margin-top:20px">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('company.index') }}">Cancel</a>

			<button type="reset" class="btn">Reset</button>

			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>