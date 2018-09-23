@extends('admin.layouts.default')
<?php
 $UTC = new DateTimeZone("UTC");
$newTZ = new DateTimeZone('America/Los_Angeles');

?>


{{-- Content --}}
@section('content')

<h2>{{$title}}</h2>
	<div class="page-header">
		
         <a href="{{route('admin.users.index')}}">All Users</a>
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead> 
			<tr>
           
            <th class="col-md-2">First Name</th>
            <th class="col-md-2">Last Name</th>
            <th class="col-md-2">User Name</th>
            
            <th class="col-md-2">EMail</th>
            <th class="col-md-2">Serviceline</th>
            <th class="col-md-2">Last Activity</th>
			</tr>
		</thead>
		<tbody>
        @foreach ($users as $user)

        <tr>
        
        <td class="col-md-2">{{ $user->person->firstname }}</td>
        <td class="col-md-2">{{ $user->person->lastname }}</td>
       	
        <td class="col-md-2">{{ $user->username }}</td>
        <td class="col-md-2">{{ $user->email }}</td>
        <td class="col-md-2"> @foreach($user->serviceline as $serviceline)
    
		    <li>{{ $serviceline->ServiceLine }}</li>
		   
		    @endforeach
    	</td>
   
 <?php 
	if(! isset($user->usage->lastactivity) or  $user->usage->lastactivity == '0000-00-00 00:00:00'  ){
		$field = NULL;
	}else{
		$date = new DateTime( $user->usage->lastactivity, $UTC );
		$date->setTimezone( $newTZ );
		$field =$date->format('Y-m-d h:i a');
	}
	
	echo " <td class=\"col-md-2\">".$field."</td>";

	?>
   
</tr>
@endforeach
		</tbody>
	</table>
    
@include('partials/_scripts')
@endsection