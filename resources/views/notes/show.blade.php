@extends('site/layouts/default')
@section('content')
<h2>My Location Notes</h2>

<p><a href="/watch" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a></p>

 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
     @foreach($fields as $title=>$field)
    <th>
    {{$title}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($notes as $note)
    <tr>  
	<?php reset($fields);?>
  @foreach($fields as $title=>$field)
    <td>
    <?php 
	
	switch ($title) {
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

    @endforeach
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')


@stop
