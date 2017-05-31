@extends('site.layouts.default')
@section('content')
<h2>{{$data['title']}}</h2>
<p>
<a href="{{route('exportlocationnotes',$companyID)}}" title="Download {{$data['title']}} as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download {{$data['title']}}</a>
</p>

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
   
    @foreach($fields as $key=>$field)
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

    @endforeach
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials._scripts')


@stop
