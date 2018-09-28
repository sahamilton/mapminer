@extends('site.layouts.default')

{{-- Content --}}
@section('content')

<div class='col-md-8'>
<h3>{{ $news->title }}</h3>
<p><a href="{{route('currentnews')}}">Return to all news</a></p>
@if(auth()->user()->hasRole('Admin'))
<div class="pull-right">
<a href="{{route('news.edit',$news->id)}}">
<button class="btn btn-info">
<i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
 Edit News</button></a>
</div>

@endif
{!! $news->news !!}</div>
<div class='col-md-12'>
<p><strong>Posted by:</strong>{{ isset($news->author) ? $news->author->person->postName() : 'No longer with the company'}}</p>

	<span class="badge badge-info">Posted {{$news->created_at->format('M jS,Y')}}</span>
</div>

<hr />

<a id="comments"></a>
<div class='col-md-6'>

<h4>{{ $news->comments->count() }} {{ \Illuminate\Support\Pluralizer::plural('Comment', $news->comments->count()) }}</h4>

@if ($news->comments)

	@foreach ($news->comments as $comment)

	<div class="row">
		
		<div class="col-md-11">
			<div class="row">
				<div class="col-md-11">
					
					<p>{{ $comment->comment }}</p>

				 <i class="fa fa-calendar" aria-hidden="true"></i> <!--Sept 16th, 2012-->{{$comment->updated_at->format('M jS,Y')}}
				
	            <i class="fa fa-user" aria-hidden="true"></i> by <span class="muted">{{ isset($comment->postedBy) ?  
	            $comment->postedBy->person->postName() :'Anonymous' }}</span>

	            @if($comment->user_id == auth()->user()->id  or auth()->user()->hasRole('Admin'))
				<a href="{{route('comment.edit',$comment->id)}}" title="Edit this comment"><i class="fa fa-pencil text-info" aria-hidden="true"></i></a> | 
				<a data-href="{{route('comment.destroy',$comment->id)}}" 
		            data-toggle="modal" 
		            data-target="#confirm-delete" 
		            data-title = "comment"  
		            title="Delete this comment"
		            href="#">
            <i class="fa fa-trash-o text-danger" aria-hidden="true"> </i> </a>
           @endif
					
				</div>
			</div>
		</div>
	</div>
	<hr />
	@endforeach
@else
	<hr />
@endif
</div>
<div class="col-md-8">
@include('news.partials.comment_form')
</div>
@include('partials._modal')
@include('partials._scripts')
@endsection
