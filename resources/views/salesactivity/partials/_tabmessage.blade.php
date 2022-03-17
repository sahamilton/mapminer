
  <div class="message">{!! $message !!}</div>
  <form id="campaignmessage" action="{{route('sendcampaign.message',$campaign->id)}}" method="post">
  {{csrf_field()}}
  <button class='disabled' >Edit Text</button>
	<div id='message' style="display:none" class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
	<label for="description">Campaign Message</label>

	<textarea required class='summernote' data-error="Please provide some description of this campaign" name="message">{!!old('message') ? old('message') : $message !!}</textarea>
	{!! $errors->first('message', '<p class="help-block">:message</p>') !!}
	</div>
  <input class="btn btn-warning" type="submit" value="Send message to team" />
  </form>
  <script>
$('.summernote').summernote({
    height: 300,                 // set editor height
    width: 500,
    minHeight: null,             // set minimum height of editor
    maxHeight: null,             // set maximum height of editor
  
    focus: true,                 // set focus to editable area after initializing summernote
    toolbar: [
    //[groupname, [button list]]
     
    ['style', ['bold', 'italic', 'underline', 'clear']],
  ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['misc',['codeview']],
  
  ]
});
</script>
