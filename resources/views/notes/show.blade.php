@extends('site/layouts/default')
@section('content')
<h2>My Location Notes</h2>

<p><a href="/watch" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a></p>

 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($title,$field) = each($fields))
    <th>
    {{$title}}
    </th>
    @endwhile
       
    </thead>
    <tbody>
   @foreach($notes as $note)
    <tr>  
	<?php reset($fields);?>
    @while(list($key,$field)=each($fields))
    <td>
    <?php 
	
	switch ($key) {
		case 'Business Name':
			$title = "See details of the ".$note->relatesTo[$field]." location";
			echo "<a href=\"/location/".$note->relatesTo['id']."\"";
			echo "\">".$note->relatesTo[$field]."</a>";
		break;
		
		case 'Created':
			
			echo date('d/m/Y',strtotime($note[$field]));
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
@include('partials/_scripts')


@stop
