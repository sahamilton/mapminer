@extends('site/layouts/default')
@section('content')

<h2>Edit Comment</h2>
@php $buttonLabel = 'Edit Comment';@endphp
<form
name="comment"
method="post"
action = "{{route('comment.update', $commet->id)}}"
>
@csrf
@method="patch"

<div class="form-group">
    <label for="comment">Feedback:</label>

<div>
    <textarea name="comment"></textarea>
        {{ $errors->first('comment') }}
    </div>
</div>
<input type="hidden" name="slug" value="{{$comment->relatesTo->slug}}" />
<input type="submit" class="btn btn-info" name="submit" value="Edit Comment" />
{{Form::close()}}
@endsection
