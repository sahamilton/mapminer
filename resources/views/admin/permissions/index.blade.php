@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')
	Permissions :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			Permission Management

			<div class="pull-right">
				<a href="{{{ route('permissions.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Permission</a>
			</div>
		</h3>
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<tr>
				<th class="col-md-6">Permission</th>
				<th class="col-md-2">Roles</th>
                <th class="col-md-2">Actions</th>
				
			</tr>
		</thead>
		<tbody>
        @foreach ($permissions as $permission)
        <tr>
        <td><a href="{{route('permissions.show',$permission->id)}}" >{{$permission->display_name}}</td>
        <td>
        @foreach ($permission->roles as $role)
        <li>{{$role->name}}</li>
        @endforeach
        <td>
        @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" permission="menu">
				
				<li><a href="{{route('permissions.edit',$permission->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit {{$permission->name}}</a></li>
				<li><a data-href="{{route('permissions.purge',$permission->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$permission->name}}" href="#"><i class="glyphicon glyphicon-trash"></i> Delete {{$permission->name}}</a></li>
			  </ul>
			</div>
        
        
        </td>
        
        </tr>
        @endforeach
		</tbody>
	</table>
    
    @include('partials/_scripts')
@stop

