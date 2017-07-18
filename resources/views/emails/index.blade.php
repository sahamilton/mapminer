@extends('admin.layouts.default')
@section('content')

<h2>All Emails</h2>

<div class="pull-right">
<a href="{{{ route('emails.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Email</a>
</div>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Created</th>
		<th>Sent</th>
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
		
		<td>{{substr(strip_tags($email->message),0,200)}}</td>
		<td>{{$email->recipientCount()}}</td>
		
		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">

					
					<li>
						<a data-href="{{route('emails.destroy',$email->id)}}" 
						data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "location" 
						href="#">
						<i class="fa fa-trash-o" aria-hidden="true"> </i> 
						Delete Email
						</a>
					</li>

				</ul>
			</div>	
		</td>
		</tr>
	@endforeach

	</tbody>
</table>
@include('partials._modal')
@include('partials/_scripts')
@stop