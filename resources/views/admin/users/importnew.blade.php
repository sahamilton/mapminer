@extends('admin.layouts.default')
@section('content')
<div class="contaainer">
	<h4>New Users to Create</h4>
<form method="post" name="createnewusers" action="{{route('import.createnewusers')}}">
	@csrf
<table class="table">
	<thead>
		<th></th>
		<th>Name</th>
		<th>Business Title</th>
		<th>Email*</th>
		<th>Username</th>
		<th>Reports To</th>
	</thead>
	<tbody>
		@foreach($newusers as $user)
		<tr>
			<td><input type="checkbox" name="enter[]" value="{{$user->employee_id}}" 
				@if($user->reports_to)
				checked
				@else
				disabled
				@endif

				/>
			<td>{{$user->firstname}} {{$user->lastname}}</td>

			<td>{{$user->business_title}}</td>
			<td>
				@if($user->reports_to)
				<input type ="text" name="email[{{$user->employee_id}}]" value="{{$user->email}}"/>
				@endif
		   </td>
		   <td>@if($user->reports_to)
		   	<input type ="text" name="username[{{$user->employee_id}}]" value="{{$user->username}}" />
		   @endif
		</td>
			<td>{{$user->manager}}
				@if(! $user->reports_to)
					<i class="fas fa-exclamation-triangle text text-danger" title="Manager does not exist in Mapminer"></i>
				
					
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>

</table>
<input type="submit" name="submit" value ="Create New Users" class="btn btn-success" />
</form>
</div>
@include('partials/_scripts')
@endsection
