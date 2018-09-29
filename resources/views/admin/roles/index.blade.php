@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@endsection

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>Role Management</h3>

			<div class="pull-right">
				<a href="{{{ route('roles.create') }}}" class="btn btn-small btn-info iframe">
<i class="fas fa-plus-circle " aria-hidden="true"></i>
 Create New Role</a>
			</div>
		
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<tr>
				<th class="col-md-6">Role</th>
				<th class="col-md-2">Permissions</th>
				<th class="col-md-2">Count</th>

                <th class="col-md-2">Actions</th>
				
			</tr>
		</thead>
		<tbody>
        @foreach ($roles as $role)
        <tr>
        <td><a href="{{route('roles.show',$role->id)}}" >{{$role->name}}</td>
        <td>
        <ul>
        @foreach($role->permissions as $permission)
        	<li>{{ucwords($permission->display_name)}}</li>
        @endforeach
        </ul>
        </td>
        <td>{{$role->assignedRoles->count()}}
        <td>
        @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<a class="dropdown-item" 
				href="{{route('roles.edit',$role->id)}}">
					<i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit {{$role->name}}
				</a>
				<a class="dropdown-item"
					data-href="{{route('roles.destroy',$role->id)}}" 
					data-toggle="modal" 
					data-target="#confirm-delete" 
					data-title = "{{$role->name}}" 
					href="#">
					<i class="far fa-trash-o text-danger" aria-hidden="true"> </i> Delete {{$role->name}}
				</a>
			  </ul>
			</div>
        
        
        </td>
        
        </tr>
        @endforeach
		</tbody>
	</table>
    
    @include('partials/_scripts')
@endsection

