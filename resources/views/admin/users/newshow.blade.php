@extends('admin.layouts.default')


{{-- Content --}}
@section('content')
	<div class="page-header">

        <h2>Mapminer Activity</h2>
@if($views[$view]['interval'])
		<h4>Users who last logged in between 
            {{$views[$view]['interval']['from']->format('M jS Y')}}
        and {{$views[$view]['interval']['to']->format('M jS Y')}}
    </h4>
    @else
<h4>Users who have never logged in</h4> 
    @endif
		<p><a href="{{route('admin.downloadlogins',$view)}}" 
            title="Download these user details as a CSV / Excel file">
            <i class="fa fa-cloud-download" aria-hidden="true"></i></i> 
            Download these user details</a> 
        </p>

        @foreach ( $views as $selectview)
        	@if($selectview['value'] != $view)
        		<a href="{{route('admin.showlogins',$selectview['value'])}}">{{$selectview['label']}}</a> | 
       	 	@else
        		{{$selectview['label']}} |
            @endif
        @endforeach
    
         <a href="{{route('users.index')}}">All Users</a>
        
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead> 
			<tr>
           
            <th class="col-md-2">First Name</th>
            <th class="col-md-2">Last Name</th>
            <th class="col-md-2">User Name</th>
            <th class="col-md-2">EMail</th>
            <th class="col-md-2">Roles</th>
            <th class="col-md-2">ServiceLine</th>

            <th class="col-md-2">Last Activity</th>
			</tr>
		</thead>
		<tbody>

        @foreach ($users as $user)

        <tr>
        
        <td class="col-md-2"><a href="{{route('users.show',$user->id)}}">{{ $user->person->firstname }}</a></td>
        <td class="col-md-2"><a href="{{route('users.show',$user->id)}}">{{ $user->person->lastname }}</a></td>
        <td class="col-md-2"><a href="{{route('users.show',$user->id)}}">{{ $user->username }}</a></td>
        <td class="col-md-2">{{ $user->email }}</td>
        <td class="col-md-2">
            @foreach ($user->roles as $role)
                {{ $role->name }}<br />
            @endforeach
        </td>
        <td class="col-md-2">
            @foreach ($user->serviceline as $serviceline)
                {{ $serviceline->ServiceLine }}<br />
            @endforeach
        </td>
        <td class="col-md-2">
			@if(isset($user->lastlogin) &&  $user->lastlogin != '0000-00-00 00:00:00'  )
               
                {{$user->lastlogin->format('M j, Y h:i a')}}
			@endif
	   </td>
</tr>
@endforeach
		</tbody>
	</table>
    
@include('partials/_scripts')
@stop