@extends('admin.layouts.default')
<?php
 $UTC = new DateTimeZone("UTC");
$newTZ = new DateTimeZone('America/Los_Angeles');

?>


{{-- Content --}}
@section('content')
	<div class="page-header">
		<h4>Users who have logged in {{$views[$view]}}</h4>
        @while (list($key,$value) = each($views))
        	@if($view != $key)
        		<a href="/admin/userlogin/{{$key}}">{{$value}}</a> | 
       	 	@else
        		{{$value}} |
            @endif
        @endwhile
         <a href="/admin/users">All Users</a>
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead> 
			<tr>
           
            <th class="col-md-2">First Name</th>
            <th class="col-md-2">Last Name</th>
            <th class="col-md-2">User Name</th>
            <th class="col-md-2">EMail</th>
            <th class="col-md-2">Last Activity</th>
			</tr>
		</thead>
		<tbody>
        @foreach ($users as $user)

        <tr>
        
        <td class="col-md-2">{{ $user['firstname'] }}</td>
        <td class="col-md-2">{{ $user['lastname'] }}</td>
        <td class="col-md-2">{{ $user['username'] }}</td>
        <td class="col-md-2">{{ $user['email'] }}</td>
        
   
 <?php 
if(! isset($user['lastlogin']) or  $user['lastlogin'] == '0000-00-00 00:00:00'  ){
		$field = NULL;
	}else{
		$date = new DateTime( $user['lastlogin'], $UTC );
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
@stop