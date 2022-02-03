@extends('admin.layouts.default')
{{-- Web site Title --}}
@section('title')
	
@endsection

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h2>Confirm Deactivation of the following users</h2>

		<form 
			method="post" 
			name="inputErrors" 
			action="{{route('users.sync.purge')}}" >
    	@csrf
		<table id ='sorttable' 
		class='table table-striped table-bordered table-condensed table-hover'>
			<thead>
				<tr>
					<th></th>
					<th>User</th>
					<th>Role</th>
					<th>Reports To</th>
					<th># Direct Reports</th>

			
				</tr>
			</thead>
			<tbody>
				
				@foreach ($users as $user)

					<tr>
						<td><input 
							type="checkbox"
							checked
							name="confirmed[]"
							value = "{{$user->id}}">
						</td>
						<td>{{$user->person->fullName()}}</td>
						<td>
							@foreach ($user->roles as $role)
								<li>{{$role->display_name}}</li>
							@endforeach
						</td>
						<td>{{$user->person->reportsTo->fullName()}}</td>
						<td>{{$user->person->directReports ? $user->person->directReports->count() : '0'}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
    	<input type="submit"
    		name="removeSelected" 
    		value="Deactivate Selected" 
    		class="form-submit btn btn-info" />
    
  	<input type="hidden"
  		name="originalusers"
  		value="{{implode(",",$users->pluck('employee_id')->toArray())}}"
  		/>
    </form>
	</div>
    

@endsection
