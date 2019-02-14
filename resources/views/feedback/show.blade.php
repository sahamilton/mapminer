@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Feedback Detail</h2>
<p><a href="{{route('feedback.index')}}">Return to all feedback</a></p>


<div class="card card-default">
	<div class="card-header">
		
		<p><strong>Type</strong>  {{$feedback->category->category}} 
			<strong>Submitted By</strong>  {{$feedback->providedBy->person->fullName()}}
		</p>
	</div>
	<div class="card-body">
		<p><strong>Feedback</strong>  {{$feedback->feedback}}</p>
	</div>
	<div class="card-footer">
		<p><strong>Created:</strong>  {{$feedback->created_at->format('Y-m-d')}}
		<strong>Posted From:</strong>  {{$feedback->url}}
		<strong>Status:</strong>  {{$feedback->status}}
		<strong>Biz Rating:</strong>  {{$feedback->biz_rating}}
		<strong>Tech Rating:</strong>  {{$feedback->tech_rating}}</p>
	</div>
</div>
<div class="float-right" style="margin-bottom: 10px">
	<a href="{{route('feedback.edit',$feedback->id)}}" class="btn btn-info">Edit Feedback</a>
</div>
<hr />
<h4>Comments</h4>
<div class="col-sm-5">
@foreach ($feedback->comments as $comment)

<div class="card card-default"  style="margin-bottom:10px">
	<div class="card-header">
		<p> {{$comment->by->person->fullName()}} <em>{{$comment->created_at->format('Y-m-d')}}</em></p>
	</div>
	<div class="card-body">
		{{$comment->comment}}
	</div>
	
</div>



@endforeach
<h5>Add new comment</h5>
<div class="newPost">
	<form method="post" name="newComment" action="{{route('feedback_comment.store')}}" >
		@csrf
		<div class="forumDivOuter" style="">
			<div id="forumDiv">
				<textarea class="autoExpand forumPost form-control" 
				rows="4" 
				data-min-rows="4"
				name="comment" 
				placeholder="Enter your message here"></textarea>
			
				<input type="hidden" name="feedback_id" value="{{$feedback->id}}" />
				<input type="submit" class="forumPostButton btn btn-info" value="Add Comment" />
				
			</div>
		</div>
	</form>		
</div>
</div>
</div>
@endsection