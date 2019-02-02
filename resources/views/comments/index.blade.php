@extends('site/layouts/default')
@section('content')

<h2>Feedback</h2>
<div class="float-right">
<a href="{{{ Route('comment.create') }}}" class="btn btn-small btn-info iframe">
<i class="fas fa-plus-circle " aria-hidden="true"></i>
Add Feedback</a>
</div>
@if (auth()->user()->hasRole('admin'))
	<a href="{{route('comment.download')}}">Download feedback to Excel</a>
@endif
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<th>Date</th>
			<th>Subject</th>
		
			<th>Feedback</th>
			<th>Status</th>
			<th>Posted By</th>
			@if(auth()->user()->hasRole('admin'))<th></th>@endif
		</thead>
		<tbody>
		@foreach($comments as $comment)
	
			<tr>  
				<td>{{$comment->created_at}}</td>
				<td>{{$comment->subject}}</td>
			
				<td>{{$comment->comment}}</td>
				<td>{{$comment->status}}</td>
				<td>{{$comment->postedBy ? $comment->postedBy->person->fullName() : 'No longer with the company'}}</td>
				@if(auth()->user()->hasRole('admin'))
					<td>
				

					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<a class="dropdown-item"
							href="{{route('comment.edit',$comment->id)}}">
							<i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit this comment</a>
							<a class="dropdown-item"
							data-href="{{route('comment.destroy',$comment->id)}}"data-toggle="modal" data-target="#confirm-delete" data-title = "this comment" href="#">
							<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete this comment</a>
						</ul>
					</div>


					</td>
				@endif
			</tr>
		@endforeach

		</tbody>
	</table>
</div>
	@include('partials/_modal')
	@include('partials/_scripts')
@endsection
