@extends('admin.layouts.default')
@section('content')

<h2>All Emails</h2>

<div class="pull-right">
<a href="{{{ route('emails.create') }}}" class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

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
						data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "email" 
						href="#">

						<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete Email</a>
				</ul>
			</div>	
		</td>
		</tr>
	@endforeach

	</tbody>
</table>
@include('partials._modal')
@include('partials._scripts')
@endsection
