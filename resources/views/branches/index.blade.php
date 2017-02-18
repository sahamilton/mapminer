@extends('site/layouts/default')
@section('content')
@if (Auth::user()->hasRole('Admin'))
<div class="pull-right">
<a href="{{{ URL::to('branch/create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Branch</a>	</div>
@endif

<h1>All Branches</h1>


<?php $route ='branch.state';?>
<p><a href="{{URL::to('branch/map')}}"><i class="glyphicon glyphicon-flag"> </i>Map View</a>
@include('branches/partials/_state')
@include('maps/partials/_form')
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @foreach($fields as $key=>$field)
    <th>
    {{$key}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($branches as $branch)
    <tr>  
	<?php reset($fields);?>
    @each($fields as $key=>$value)
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
			if(!is_null($branch->manager)){
				$name = $branch->manager->firstname . " " . $branch->manager->lastname;
				$title = "See all branches managed by ".$name;
				echo "<a href=\"/person/".$branch->manager->id."\" title=\"".$title."\">".$name."</a>";
			}
		break;
		
		case 'Email':
			if(!is_null($branch->manager)){
				$name = $branch->manager->firstname . " " . $branch->manager->lastname;
				$title="Send email to ".$name;
				echo "<a href=\"mailto:".$branch->manager->email."\" title=\"".$title."\">".$branch->manager->email."</a>";
			}
		break;
		
		case 'Service Line':
			if(!is_null($branch->servicelines)){
				foreach($branch->servicelines as $serviceline){
					$title="See all ".$serviceline->ServiceLine ." branches";
					echo "<a href=\"/serviceline/".$serviceline->id."\" title=\"".$title."\">".$serviceline->ServiceLine."</a>";
				}
			}
		break;

		case 'Serviced':
		$title= " See the " . $branch->branchname . " branch sales team";
				echo "<a title=\"".$title. "\" href =\"".route('showlist/salesteam',$branch->id)."\">".count($branch->servicedBy)."</a>";

		break;
		
		case 'Branch Address':
			echo $branch->street ." " .$branch->address2;
		
		break;
		
		case 'State':
				$title="See all ".$branch->state." state branches";
				echo "<a href=\"/branch/state/".$branch->state."\" title=\"".$title."\">".$branch->state."</a>";

		break;
		
		case 'Region':
			if(!is_null($branch->region)){
				$title="See all ".$branch->region->region." region branches";
				echo "<a href=\"/region/".$branch->region->id."\" title=\"".$title."\">".$branch->region->region."</a>";
			}
		break;
		
		case 'Actions':

			?>
            @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="/branch/{{$branch->id}}/edit/"><i class="glyphicon glyphicon-pencil"></i> Edit {{$branch->branchname}} Branch</a></li>
				<li><a data-href="/branch/{{$branch->id}}/delete" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$branch->branchname}} branch" href="#"><i class="glyphicon glyphicon-trash"></i> Delete {{$branch->branchname}} branch</a></li>
			  </ul>
			</div>
		
		<?php

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
