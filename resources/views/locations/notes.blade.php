@extends('site/layouts/default')
@section('content')
<div>
<h1>All Location Notes</h1>
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($key,$field)=each($fields))
    <th>
    {{$key}}
    </th>
    @endwhile
       
    </thead>
    <tbody>
   @foreach($notes as $note)
    <tr>  
	<?php reset($fields);?>
    @while(list($key,$field)=each($fields))
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
    @endwhile
    
    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
    </div>
@include('partials/_scripts')
@stop