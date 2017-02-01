@extends('site/layouts/default')
@section('content'
)<?php $type='list';?>
@include('branches/partials/_head')

 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($title,$field) = each($fields))
    <th>
    {{$title}}
    </th>
    @endwhile
       
    </thead>
    <tbody>
   @foreach($locations as $location)
    <tr>  
	<?php reset($fields);?>
    @while(list($key,$field)=each($fields))
    <?php 
	
	switch ($key) {
		case 'Business Name':
			$title = "See details of the ".$location[$field]." location";
			echo "<td><a href=\"/location/".$location['id']."\"";
			echo " title=\"".$title."\">".$location[$field]."</a></td>";
		break;
		
		case 'Watching':
			echo "<td style =\"text-align: center; vertical-align: middle;\">";
			if(in_array($location['id'],$mywatchlist)){
				echo "<input checked";
				
			}else{
				echo "<input ";
			}
			echo " id=\"".$location['id']."\" ";
			echo " type='checkbox' name='watchList' class='watchItem' ";
			echo " value='".$location['id']."' ></td>";
		break;		
		
		default:
			echo "<td>".$location[$field]."</td>";
		break;
		
	};?>
	

    @endwhile
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')


@stop