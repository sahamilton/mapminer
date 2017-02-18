@extends('site/layouts/default')
@section('content')
<div>
<h1>All Location Notes</h1>
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @foreach($fields as $key=>$value)
    <th>
    {{$key}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($notes as $note)
    <tr>  
	<?php reset($fields);?>
    @foreach($fields as $key=>$field)
    <td><?php 
	
		
	switch ($key) {
		
		case 'Location Name':
			echo "<a href=\"/location/".$note['locationid']."\" title =\"Review notes at this location\" >".$note[$field]."</a>";
		
		break;
		
		
		
		default:
			echo $note[$field];
		break;
		
	};?>
	
    </td>
    @endforeach
    
    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
    </div>
@include('partials/_scripts')
@stop