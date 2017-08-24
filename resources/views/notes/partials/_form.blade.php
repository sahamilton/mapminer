
<form id="notesForm" method="post" action = "{{route('notes.store')}}" >
{{csrf_field()}}
<div>
{{Form::label('note','Add a Note:')}}
<div>
<textarea name="note"></textarea>
{{ $errors->first('note') }}
</div></div>
<input type = "hidden" name="related_id" value="{{$id}}" />
<input type = "hidden" name="type" value="{{$type}}" />
<button type="submit" class="btn btn-success">Add New Note</button>
</form>
