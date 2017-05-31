@extends('site/layouts/default')
@section('content')

<h2> {{$people->firstname}} {{$people->lastname}}</h2>
<p>Reports to: 
<a href="{{route('person.show',$people->reportsTo->id)}}">
		
		
{{$people->reportsTo->firstname}} {{$people->reportsTo->lastname}}</a>
<p><a href="mailto:{{$people->email}}" title="Email {{$people->firstname}} {{$people->lastname}}">{{$people->email}}</a> </p>
<h4>Branches serviced by {{$people->firstname}} {{$people->lastname}}</h4>

  <p><a href="{{route('showmap.person',$people->id)}}"><i class="glyphicon glyphicon-flag"></i> Map View</a></p>	
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @foreach($fields as $key=>$field)
    <th>
    {{$key}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($people->branchesServiced as $branch)
    <tr>  
	<?php reset($fields);?>
   @foreach($fields as $key=>$field)
    <td>
	<?php 
	
	switch ($key) {
		case 'Branch':
			$title = "See details of branch ".$branch->$field;
			echo "<a href=\"/branch/".$branch->id."\" title=\"".$title."\">".$branch->$field."</a>";
		break;
		
		
		
		case 'Service Line':
			foreach($branch->servicelines as $serviceline){
				$title="See all ".$serviceline->ServiceLine ." branches";
				echo "<a href=\"/serviceline/".$serviceline->id."\" title=\"".$title."\">".$serviceline->ServiceLine."</a>";
			}
		break;
		
		case 'Branch Address':
			echo $branch->street ." " .$branch->address2;
		
		break;
		
		case 'State':
				$title="See all ".$branch->state." branches";
				echo "<a href=\"/branch/state/".$branch->state."\" title=\"".$title."\">".$branch->state."</a>";

		break;
		
		
		
		default:
			echo $branch->$field;
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