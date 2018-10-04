@extends('admin.layouts.default')
{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop
 @include('partials/_modal')
{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>{{ $title }}</h3>
			
				
			@if($serviceline != 'All')
				<h6><a href="{{route('users.index')}}">See All Users</a></h6>
			@endif
			<div class="pull-right">

				<a href="{{{ route('users.create') }}}" class="btn btn-small btn-info iframe">
				
<i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
 Create</a>

			</div>
		
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<tr>
            <th class="col-md-2">id</th>
            <th class="col-md-2">First Name</th>
            <th class="col-md-2">Last Name</th>
            <th class="col-md-2">User Name</th>
            <th class="col-md-2">EMail</th>
            <th class="col-md-2">Roles</th>
            <th class="col-md-2">Service Lines</th>
            <th class="col-md-2">Activated</th>
            <th class="col-md-2">LastLogin</th>
            <th class="col-md-2">LastUpdate</th>
            <th class="col-md-2">Actions</th>
			</tr>
		</thead>
		<tbody>
        @foreach ($users as $user)

        <tr>
        <td class="col-md-2">{{ $user->id }}</td>
        <td class="col-md-2">
        @if(isset($user->person->firstname))
        <a href="{{route('users.show',$user->id)}}">{{$user->person->firstname}}</a>
        @endif
        </td>
        <td class="col-md-2">
        @if(isset($user->person->lastname))
        <a href="{{route('users.show',$user->id)}}">{{$user->person->lastname}}</a>
        @endif
        </td>
	<td class="col-md-2">{{ $user->username }}</td>
    <td class="col-md-2">{{ $user->email }}</td>
    <td class="col-md-2">
    <ul>
    @foreach($user->roles as $role)
    
    <li><a title="Show all {{$role->name}} users" href="{{route('roles.show',$role->id)}}">{{ $role->name }}</a></li>
   
    @endforeach
    </ul>
    </td>
    <td class="col-md-2">
    <ul>
    @if(isset($user->serviceline))
    @foreach($user->serviceline as $serviceline)
    
    <li><a href="{{route('serviceline.show',$serviceline->id)}}"> {{$serviceline->ServiceLine }}</a></li>
   
    @endforeach
    @endif
    </ul>
    </td>
    <td class="col-md-2">{{ $user->confirmed == '1' ? "yes" :  "no"}}</td>
    <td>
 
		@if(isset($user->lastlogin) &&  $user->lastlogin != '0000-00-00 00:00:00'  )
               
                {{$user->lastlogin->format('M j, Y h:i a')}}
			@endif
	
	</td>
    <td>{{$user->updated_at ? $user->updated_at->format('M j, Y h:i a') : ''}}</td>
    <td class="col-md-2">
    

            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="{{route('users.edit',$user->id)}}"><i class="fa fa-pencil" aria-hidden="true"> </i>Edit {{$user->person->firstname}}  {{$user->person->lastname}}</a></li>

				<li><a data-href="{{route('users.destroy',$user->id)}}" 
				data-toggle="modal" 
				data-target="#confirm-delete" 
				data-title = "{{$user->person->firstname}}  {{$user->person->lastname}}" href="#">
				<i class="fa fa-trash-o" aria-hidden="true"> </i> 
				Delete {{$user->person->firstname}}  {{$user->person->lastname}}</a></li></a></li>

			  </ul>
			</div>
            </td>
</tr>
@endforeach
		</tbody>
	</table>
    
@include('partials/_scripts')
@stop
