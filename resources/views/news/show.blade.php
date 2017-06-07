@extends('site.layouts.default')

{{-- Content --}}
@section('content')
<div class='col-md-8'>
<h3>{{ $news->title }}</h3>

{!! $news->news !!}</div>
<div class='col-md-12'>
<p><strong>Posted by:</strong>{{ isset($news->author) ? $news->author->person->postName() : 'No longer with the company'}}</p>

	<span class="badge badge-info">Posted {{$news->created_at->format('M jS,Y')}}</span>
</div>

<hr />

<a id="comments"></a>
<div class='col-md-6'>

<h4>{{ $news->comments->count() }} {{ \Illuminate\Support\Pluralizer::plural('Comment', $news->comments->count()) }}</h4>

@if (count($news->comments) > 0)

	@foreach ($news->comments as $comment)

	<div class="row">
		
		<div class="col-md-11">
			<div class="row">
				<div class="col-md-11">
					
					<p>{{ $comment->comment }}</p>

				 <span class="glyphicon glyphicon-calendar"></span> <!--Sept 16th, 2012-->{{$comment->updated_at->format('M jS,Y')}}
				
	            <span class="glyphicon glyphicon-user"></span> by <span class="muted">{{ isset($comment->postedBy) ?  
	            $comment->postedBy->person->postName() :'Anonymous' }}</span>
					
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

@stop
