@extends('site/layouts/default')
@section('content')

<h2>Feedback</h2>
<div class="pull-right">
<a href="{{{ Route('comment.create') }}}" class="btn btn-small btn-info iframe">

<i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
Add Feedback</a>
</div>
@if (auth()->user()->hasRole('Admin'))
	<a href="{{route('comment.download')}}">Download feedback to Excel</a>
@endif
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<th>Date</th>
			<th>Subject</th>
			<th>Title</th>
			<th>Feedback</th>
			<th>Status</th>
			<th>Posted By</th>

		</thead>
		<tbody>
		@foreach($comments as $comment)
			<tr>  
				<td>{{$comment->created_at}}</td>
				<td>{{$comment->subject}}</td>
				<td>{{title}}</td>
				<td>{{comment}}</td>
				<td>{{comment_status}}</td>
				<td>{{user_id}}</td>
				@if(auth()->user()->hasRole('Admin'))
					<td>
					@include('partials/_modal')

					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{route('comment.edit',$comment->id)}}">
							<i class="fa fa-pencil" aria-hidden="true"> </i>Edit this comment</a></li>
							<li><a data-href="{{route('comment.destroy',$comment->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "this comment" href="#">
							<i class="fa fa-trash-o" aria-hidden="true"> </i> Delete this comment</a></li>
						</ul>
					</div>


					</td>
				@endif
			</tr>
		@endforeach

		</tbody>
	</table>
</div>
@include('partials/_scripts')
@endsection