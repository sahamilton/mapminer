@extends ('site.layouts.default')
@section('content')

<h1>Latest News and Comments</h1>

@if(auth()->user()->hasRole('Admin'))
<div class="pull-right">
    <a href="{{ route('news.create') }}" class="btn btn-small btn-info iframe">
        <i class="fa fa-plus text-success" aria-hidden="true"></i>
        Add New Updates
    </a>
</div>
@endif
@foreach ($news as $post)
<div class="row">
	<div class="col-md-8">
		<!-- Post Title -->
		<div class="row">
			<div class="col-md-8">
				<h4>
				<strong>
				<a href="{{route('news.show', trim($post->slug))}}">
				{{ $post->title }}
				</a>
				</strong>
				</h4>
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
			@if(auth()->user()->hasRole('Admin'))
				<p>Visible to <a href="{{route('news.audience',$post->id)}}"
				title="See all users who can see the {{$post->title}} news item">{{count($post->audience($post->id))}} users.</a></p>
				@endif
				<p><i class="fa fa-user" aria-hidden="true"></i> by <span class="muted">
				@if(isset($post->author))
					{{$post->author->person->fullName()}}
				@else
					No Longer with the company
				@endif
				| </span>
					<i class="fa fa-calendar" aria-hidden="true"></i> <!--Sept 16th, 2012-->
					{{$post->datefrom->format('M jS,Y')}}
					| <i class="fa fa-comment-o" aria-hidden="true"></i> 
					<a href="{{route('news.show', $post->slug)}}#comments"> {{$post->comments->count()}}</a>

					@if($post->user_id == auth()->user()->id  or auth()->user()->hasRole('Admin'))
<a href="{{route('news.edit',$post->id)}}" title="Edit this news item"><i class="fa fa-pencil" aria-hidden="true"></i></a> | 
<a data-href="{{route('news.destroy',$post->id)}}" 
            data-toggle="modal" 
            data-target="#confirm-delete" 
            data-title = "news item"  
            title="Delete this news item"
            href="#">
            <i class="fa fa-trash-o" aria-hidden="true"> </i> </a>
           

				@endif
				</p>
			</div>
		</div>
		<!-- ./ post footer -->
	</div>
</div>

<hr />
@endforeach









{{-- Scripts --}}
@include('partials._modal')
@include('partials._scripts')
@stop
