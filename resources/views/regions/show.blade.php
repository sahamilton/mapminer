@extends('site/layouts/default')
@section('content')
<h1>{{$data['region']->region}} Region Branches</h1>

<h4> <a href="{{ URL::to('branch') }}">Show all branches</a></h4>	
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($key,$field)=each($fields))
    <th>
    {{$key}}
    </th>
    @endwhile
    
    </thead>
    <tbody>
   @foreach($branches as $branch)
    <tr>  
	<?php reset($fields);?>
    @while(list($key,$field)=each($fields))
    <td>
	<?php 
	/*'Number'=>'branchnumber',
						'Service Line'=>'brand',
						'Branch Address'=>'street',
						'City'=>'city',
						'State'=>'state',
						'Region'=>'region',
						'Manager'=>'manager');*/

	switch ($key) {
		case 'Branch':
			$title = "See details of branch ".$branch->$field;
			echo "<a href=\"/branch/".$branch->id."\" title=\"".$title."\">".$branch->$field."</a>";
		break;
		
		case 'Manager':
			if(isset($branch->manager->firstname)) {
			$name = $branch->manager->firstname . " " . $branch->manager->lastname;
			$title = "See all branches managed by ".$name;
			echo "<a href=\"/person/".$branch->manager->id."\" title=\"".$title."\">".$name."</a>";
			}
		break;
		
		case 'Email':
			$name = $branch->manager->firstname . " " . $branch->manager->lastname;
			$title="Send email to ".$name;
			echo "<a href=\"mailto:".$branch->manager->email."\" title=\"".$title."\">".$branch->manager->email."</a>";
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
    @endwhile
    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')
@stop
