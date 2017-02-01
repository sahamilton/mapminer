@extends('site/layouts/default')
@section('content')
@if (Auth::user()->hasRole('Admin'))
<?php $fields['Actions']='actions';?>
<div class="pull-right">
				<a href="{{{ URL::to('branch/create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Branch</a>	</div>
@endif
<h1>All {{$serviceline->ServiceLine}} Branches</h1>
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
		
		case 'Branch Address':
			echo $branch->street ." " .$branch->address2;
		
		break;
		
		case 'State':
				$title="See all ".$branch->state." branches";
				echo "<a href=\"/branch/state/".$branch->state."\" title=\"".$title."\">".$branch->state."</a>";

		break;
		
		case 'Region':
			if(!is_null($branch->region)){
				$title="See all ".$branch->region->region." branches";
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
    @endwhile
    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')
@stop

