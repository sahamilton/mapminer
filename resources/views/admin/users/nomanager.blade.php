
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
            <th class="col-md-2">Actions</th>
			</tr>
		</thead>
		<tbody>
        @foreach ($people as $person)

        <tr>
        <td class="col-md-2">{{ $person->userdetails->id }}</td>
        <td class="col-md-2">
        @if(isset($person->firstname))
        <a href="{{route('users.show',$person->userdetails->id)}}">{{$person->firstname}}</a>
        @endif
        </td>
        <td class="col-md-2">
        @if(isset($person->lastname))
        <a href="{{route('users.show',$person->userdetails->id)}}">{{$person->lastname}}</a>
        @endif
        </td>
	<td class="col-md-2">{{ $person->userdetails->username }}</td>
    <td class="col-md-2">{{ $person->userdetails->email }}</td>
    <td class="col-md-2">
    <ul>
    @foreach($person->userdetails->roles as $role)
    
    <li><a title="Show all {{$role->name}} users" href="{{route('roles.show',$role->id)}}">{{ $role->name }}</a></li>
   
    @endforeach
    </ul>
    </td>
    <td class="col-md-2">
    <ul>
    @if(isset($person->userdetails->serviceline))
    @foreach($person->userdetails->serviceline as $serviceline)
    
    <li><a href="{{route('serviceline.show',$serviceline->id)}}"> {{$serviceline->ServiceLine }}</a></li>
   
    @endforeach
    @endif
    </ul>
    </td>
    <td class="col-md-2">{{ $person->userdetails->confirmed == '1' ? "yes" :  "no"}}</td>
    <td>
 
		@if(isset($person->userdetails->lastlogin) &&  $person->userdetails->lastlogin != '0000-00-00 00:00:00'  )
                <?php  $lastlogin = Carbon\Carbon::parse($person->userdetails->lastlogin);?>
                {{$lastlogin->format('M j, Y h:i a')}}
			@endif
	
	</td>
    <td class="col-md-2">
    

            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="{{route('users.edit',$person->userdetails->id)}}"><i class="fa fa-pencil" aria-hidden="true"> </i>Edit {{$person->firstname}}  {{$person->lastname}}</a></li>

				<li><a data-href="{{route('users.destroy',$person->userdetails->id)}}" 
				data-toggle="modal" 
				data-target="#confirm-delete" 
				data-title = "{{$person->firstname}}  {{$person->lastname}}" href="#">
				<i class="fa fa-trash-o" aria-hidden="true"> </i> 
				Delete {{$person->firstname}}  {{$person->lastname}}</a></li></a></li>

			  </ul>
			</div>
            </td>
</tr>
@endforeach
		</tbody>
	</table>
    
@include('partials/_scripts')
@stop
