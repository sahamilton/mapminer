@extends('admin.layouts.default')
@section('content')

<h2>All Emails</h2>

<div class="pull-right">
<a href="{{{ route('emails.create') }}}" class="btn btn-small btn-info iframe">
<<<<<<< HEAD
<i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
=======
<i class="fas fa-plus-circle " aria-hidden="true"></i>
>>>>>>> development
 Create New Email</a>
</div>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		
		<th>Created</th>
		<th>Sent</th>
		<th>Subject</th>
		<th>Body</th>
		<th>Recipients</th>
		<th>Actions</th>

	</thead>
	<tbody>
	@foreach($emails as $email)
		<tr>  
		<td>{{$email->created_at->format('M j, Y')}}</td>
		<td>
		@if(isset($email->sent))
		{{$email->sent->format('M j, Y')}}
		@endif
		</td>
		<td>{{$email->subject}}</td>
		
		<td>{{substr(strip_tags($email->message),0,200)}}</td>
		<td>
		<a href="{{route('emails.recipients',$email->id)}}"
		title="See the recipients list for this email">{{$email->recipientCount()}}</a></td>
		
		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">
<<<<<<< HEAD
				<li>
				@if(! $email->sent)
					<a href="{{route('emails.edit',$email->id)}}" title="Edit this email">
					<i class="fa fa-pencil" aria-hidden="true"></i>
					Edit this email</a>
				@else
					<a href="{{route('emails.clone',$email->id)}}" title="Clone this email"><i class="fa fa-refresh" aria-hidden="true"></i>
					Clone this email</a>
				@endif
				</li>
					
					<li>
						<a data-href="{{route('emails.destroy',$email->id)}}" 
=======
				
				@if(! $email->sent)
					<a class="dropdown-item"
					href="{{route('emails.edit',$email->id)}}" 
					title="Edit this email">
					<i class="far fa-edit text-info"" aria-hidden="true"></i>
					Edit this email
				</a>
				@else
					<a class="dropdown-item"
					 href="{{route('emails.clone',$email->id)}}" title="Clone this email"><i class="far fa-copy text-info" aria-hidden="true"></i>
					Clone this email</a>
				@endif
				
					<a class="dropdown-item"
					 data-href="{{route('emails.destroy',$email->id)}}" 
>>>>>>> development
						data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "email" 
						href="#">
<<<<<<< HEAD
						<i class="fa fa-trash-o" aria-hidden="true"> </i> Delete Email</a>
					</li>

=======
						<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete Email</a>
>>>>>>> development
				</ul>
			</div>	
		</td>
		</tr>
	@endforeach

	</tbody>
</table>
@include('partials._modal')
@include('partials._scripts')
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
