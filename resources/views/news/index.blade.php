@extends ('site.layouts.default')
@section('content')

<h1>Updates</h1>

<div class="pull-right">
    <a href="{{{ route('news.create') }}}" class="btn btn-small btn-info iframe">
        <span class="glyphicon glyphicon-plus-sign"></span> Add New Updates</a>
</div>
@foreach ($news as $post)
<div class="row">
	<div class="col-md-8">
		<!-- Post Title -->
		<div class="row">
			<div class="col-md-8">
				<h4><strong><a href="{{route('news.show', trim($post->slug))}}">{{ $post->title }}</a></strong></h4>
			</div>
		</div>
		<!-- ./ post title -->

		<!-- Post Content -->
		<div class="row">
			<div class="col-md-2">
				
			</div>
			<div class="col-md-6">
				<p>
					{!! str_limit($post->news, 200) !!}
				</p>
				<p><a class="btn btn-mini btn-default" href="{!!route('news.show', $post->slug)!!}">Read more</a></p>
			</div>
		</div>
		<!-- ./ post content -->

		<!-- Post Footer -->
		<div class="row">
			<div class="col-md-8">
				<p></p>
				<p>

					<span class="glyphicon glyphicon-user"></span> by <span class="muted">{{{$post->author->person->firstname}}} {{{$post->author->person->lastname}}}</span>
					| <span class="glyphicon glyphicon-calendar"></span> <!--Sept 16th, 2012-->{{{date('M jS,Y',strtotime($post->startdate))}}}
					| <span class="glyphicon glyphicon-comment"></span> <a href="/news/{{{ $post->slug}}}#comments"> {{{$post->comments->count()}}}</a>
				</p>
			</div>
		</div>
		<!-- ./ post footer -->
	</div>
</div>

<hr />
@endforeach

{{ $news->links() }}






{{-- Scripts --}}
@include('partials._scripts')
@stop
