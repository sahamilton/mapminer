@extends('site.layouts.default')

{{-- Content --}}
@section('content')
<div class='col-md-8'>
<h3>{{ $news[0]->title }}</h3>

{{ $news[0]->news }}</div>
<div class='col-md-12'>
<p><strong>Posted by:</strong>{{ $news[0]->author->person->firstname }} {{ $news[0]->author->person->lastname }}</p>

	<span class="badge badge-info">Posted {{{date('M jS,Y',strtotime($news[0]->startdate))}}}</span>
</div>

<hr />

<a id="comments"></a>
<div class='col-md-6'>
<h4>{{ $news[0]->comments->count() }} {{ \Illuminate\Support\Pluralizer::plural('Comment', $news[0]->comments->count()) }}</h4>

@if ($news[0]->comments->count())
@foreach ($news[0]->comments as $comment)

<div class="row">
	
	<div class="col-md-11">
		<div class="row">
			<div class="col-md-11">
				
				<p>{{ ($comment->comment) }}</p>

			 <span class="glyphicon glyphicon-calendar"></span> <!--Sept 16th, 2012-->{{{date('M jS,Y',strtotime($comment->updated_at))}}}
			
            <span class="glyphicon glyphicon-user"></span> by <span class="muted">{{{ isset($comment->postedBy->person->firstname) ?  
            $comment->postedBy->person->firstname . " " .$comment->postedBy->person->lastname :'Anonymous' }}}</span>
				
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
