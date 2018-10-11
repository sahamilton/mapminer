@extends('admin.layouts.default')
{{-- Content --}}
@section('content')

<h2>{{$title}}</h2>
	<div class="page-header">
		
         <a href="{{route('users.index')}}">All Users</a>
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead> 
			<tr>
           
            <th>First Name</th>
            <th>Last Name</th>
            <th>User Name</th>
            
            <th>EMail</th>
            <th>Serviceline</th>
            <th>Last Activity</th>
			</tr>
		</thead>
		<tbody>
        @foreach ($users as $user)

        <tr>
        
        <td><a href="{{route('users.show',$user->id)}}">{{ $user->person->firstname }}</a></td>
        <td><a href="{{route('users.show',$user->id)}}">{{ $user->person->lastname }}</td>
       	
        <td><a href="{{route('users.show',$user->id)}}">{{ $user->username }}</a></td>
        <td>{{ $user->email }}</td>
        <td> @foreach($user->serviceline as $serviceline)
    
		    <li>{{ $serviceline->ServiceLine }}</li>
		   
		    @endforeach
    	</td>

    	 <td>{{$user->lastlogin ? $user->lastlogin->format('Y-m-d h:i a'):''}}</td>

</tr>
@endforeach
		</tbody>
	</table>
    
@include('partials/_scripts')
@endsection
