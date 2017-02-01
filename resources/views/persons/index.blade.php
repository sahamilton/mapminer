@extends('site/layouts/default')
@section('content')

<h1>All Managers</h1>
@include('partials/_showsearchoptions')
@include('partials/advancedsearch')

 <p><a href="{{URL::to('people/map')}}"><i class="glyphicon glyphicon-flag"> </i>Map View</a>

@if (Auth::user()->hasRole('Admin'))
<div class="pull-right">
				<a href="{{{ URL::to('admin/users/create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Person</a>
			</div>
@endif
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($key,$field)=each($fields))
    <th>
    {{$key}}
    </th>
    @endwhile
       
    </thead>
    <tbody>
   @foreach($persons as $person)

    <tr>  
	<?php reset($fields);?>
    @while(list($key,$field)=each($fields))
    <td>
	<?php 
	$name = $person->firstname . " " . $person->lastname;
	switch ($key) {
				
		case 'Name':
			
			
			echo "<a href=\"".route('person.show',$person->id)."\">";
			echo $name;
			echo "</a>";
		break;
		
		case 'Email':
			
			$title="Send email to ".$name;
			echo "<a href=\"mailto:".$person->userdetails->email."\" title=\"".$title."\">".$person->userdetails->email."</a>";
		break;
		
		case 'Role':
			foreach ($person->userdetails->roles as $role){
			echo ($role->name != 'User') ? $role->name ."<br />":'';

			}
		break;
		
		case 'Industry':
			
			foreach ($person->industryfocus as $industry)
			{
				if(strtolower($industry->filter) == 'not specified')
				{
				echo "<li>General</li>";

				}else{
					echo "<li>" . $industry->filter . "</li>";
				}

				
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
				
				<li><a href="/person/{{$person->id}}/edit/"><i class="glyphicon glyphicon-pencil"></i> Edit {{$name}} Branch</a></li>
				<li><a data-href="/person/{{$person->id}}/delete" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$name}}" href="#">
                <i class="glyphicon glyphicon-trash"></i> Delete {{$name}} branch</a></li>
			  </ul>
			</div>
		
		<?php

		break;	
		
		
		default:
			echo $person->$field;
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