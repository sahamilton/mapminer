@extends('admin.layouts.default')
@section('content')
<h2>Deleted Users</h2>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<tr>
          
            <th class="col-md-2">User</th>
            <th class="col-md-2">EMail</th>
            <th class="col-md-2">Roles</th>
            <th class="col-md-2">Date Deleted</th>
            <th class="col-md-2">Actions</th>
			</tr>
		</thead>
		<tbody>
        @foreach ($users as $user)

        <tr>
        
        <td class="col-md-2">
        @if(isset($user->deletedperson))
        {{$user->deletedperson->fullName()}}
        @endif
        </td>
       
    <td class="col-md-2">{{ $user->email }}</td>
    <td class="col-md-2">
    <ul>
    @foreach($user->roles as $role)
    
    <li><a title="Show all {{$role->display_name}} users" href="{{route('roles.show',$role->id)}}">{{ $role->display_name }}</a></li>
   
    @endforeach
    </ul>
    </td>
    

    

    <td>{{$user->deleted_at->format('Y-m-d h:i a')}}</td>
   

    <td class="col-md-2">
    

            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<a class="dropdown-item"
                href="{{route('users.restore',$user->id)}}">
                <i class="far fa-edit text-info"" 
                aria-hidden="true"> </i>Restore {{ $user->deletedperson->fullName()}}</a>

				<a class="dropdown-item" 
                data-href="{{route('users.permdestroy',$user->id)}}" 
				data-toggle="modal" 
				data-target="#confirm-delete" 
				data-title = "{{$user->deletedperson->fullName()}}" href="#">
				<i class="far fa-trash-alt text-danger" 
                aria-hidden="true"> </i> 
				Permanently Delete  {{$user->deletedperson->fullName()}}</a>

			  </ul>
			</div>
            </td>
</tr>
@endforeach
		</tbody>
	</table>
    
@include('partials/_scripts')
 @include('partials/_modal')
@endsection
