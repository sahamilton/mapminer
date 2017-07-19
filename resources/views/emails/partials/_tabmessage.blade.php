<div style="margin-top:20px">
<form id="emailmessage" action="{{route('emails.store')}}" method="post">
  {{csrf_field()}}
  
<!-- subject -->
    <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Subject:</label>
            <div class="input-group input-group-lg" >
                <input required type="text" class="form-control" name='subject' description="subject" value="{{ old('subject') ? old('subject') : isset($email->subject) ? $email->subject : "" }}" placeholder="subject">
                <span class="help-block">
                    <strong>{{ $errors->has('subject') ? $errors->first('subject') : ''}}</strong>
                    </span>
            </div>
    </div>
 


	<div id='message' class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
	<label class="col-md-2 control-label" for="description">Email Message Message</label>
<div class="input-group input-group-lg" >
	<textarea required class='summernote' data-error="Please provide some text for this email" name="message">{!! old('message') ? old('message') : isset($email->message) ? $email->message : '' !!}</textarea>
	{!! $errors->first('message', '<p class="help-block">:message</p>') !!}
	</div>
  </div>
  <input class="btn btn-warning" type="submit" value="Save message" />
  </form>
  </div>
  