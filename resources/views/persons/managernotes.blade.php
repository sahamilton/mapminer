@extends('site/layouts/default')
@section('content')
<h2>{{$data['title']}}</h2>
<p>
<a href="{{route('exportlocationnotes',$companyID)}}" title="Download {{$data['title']}} as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download {{$data['title']}}</a>
</p>

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
		case 'Location Name':
			echo "<a href=\"".route('location.show',$note['locationid'])."\">";
			echo $note[$field]."</a>";
		
		
		break;
		
		
		case 'Posted':
			
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
