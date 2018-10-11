@extends('site/layouts/default')
@section('content')
<h4> Edit Note</h4>
<form method="post" action = "{{route('notes.update', $note->id)}}" >
{{csrf_field()}}
<input type= 'hidden' name="_method" value="patch" >

<div>
{{Form::label('note','Notes:')}}
<div>
<textarea name="note">{{$note->note}}</textarea>
{{ $errors->first('note') }}
</div></div>

<button type="submit" class="btn btn-success">Edit Note</button>
</form>
</div>
</div>
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
