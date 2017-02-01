@extends('site/layouts/default')
@section('content')
<h4> Edit Note for </h4>

{{Form::model($note, ['method'=>'PATCH','route'=>['notes.update', $note->id]]) }}

<div>
{{Form::label('note','Notes:')}}
<div>
{{Form::textarea('note')}}
{{ $errors->first('note') }}
</div></div>
{{Form::hidden('location_id',$note->location_id)}}
<button type="submit" class="btn btn-success">Edit Note</button>
{{Form::close()}}
</div>
</div>
@stop