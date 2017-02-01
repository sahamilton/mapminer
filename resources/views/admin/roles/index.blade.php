@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			Role Management

			<div class="pull-right">
				<a href="{{{ URL::to('admin/roles/create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Role</a>
			</div>
		</h3>
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<tr>
				<th class="col-md-6">{{{ Lang::get('admin/roles/table.name') }}}</th>
				<th class="col-md-2">{{{ Lang::get('admin/roles/table.users') }}}</th>
                <th class="col-md-2">Actions</th>
				
			</tr>
		</thead>
		<tbody>
        @foreach ($roles as $role)
        <tr>
        <td><a href="{{url('admin/roles/'.$role->id.'/show')}}" >{{$role->name}}</td>
        <td>{{$role->assignedRoles->count()}}
        <td>
        @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="/admin/roles/{{$role->id}}/edit/"><i class="glyphicon glyphicon-pencil"></i> Edit {{$role->name}}</a></li>
				<li><a data-href="/admin/roles/{{$role->id}}/delete" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$role->name}}" href="#"><i class="glyphicon glyphicon-trash"></i> Delete {{$role->name}}</a></li>
			  </ul>
			</div>
        
        
        </td>
        
        </tr>
        @endforeach
		</tbody>
	</table>
    
    @include('partials/_scripts')
@stop

