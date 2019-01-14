
<form id="notesForm" method="post" action = "{{route('notes.store')}}" >
@csrf
<div>
<form action ="{{note.store}} name="addNote" method="post" >

<div>
<textarea name="note"></textarea>
{{ $errors->first('note') }}
</div></div>
<input type = "hidden" name="address_id" value="{{$id}}" />
