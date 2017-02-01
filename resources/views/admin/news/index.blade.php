@extends('admin/layouts/default')
@section('content')

<h1>All Updates</h1>

<div class="pull-right">
				<a href="{{{ route('admin.news.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Updates</a>
			</div>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($key,$field)=each($fields))
    <th>
    {{$key}}
    </th>
    @endwhile
       
    </thead>
    <tbody>
   @foreach($news as $item)
    <tr>  
	<?php reset($fields);
	$now = date('Y-m-d h:i:s');
     while(list($key,$field)=each($fields)){

	if($item->startdate > $now or $item->enddate < $now) {
		echo "<td class='danger'>";
	}else{
		echo "<td class='success'>";
	}

	
	$name = $item->firstname . " " . $item->lastname;
	switch ($key) {
				
		
		
		case 'Content':
			echo strlen($item->$field) >200 ? substr($item->$field,0,200) . '<em>...more</em>' : $item->$field;
		
		break;

		case 'Serviceline':
		echo "<ul>";
			foreach ($item->serviceline as $serviceline)
			{
				echo "<li>" . $serviceline->ServiceLine. "</li>";
			}	
			 echo "</ul>";
		break;
	
		case 'Comments':
			echo $item->comments->count() ." comments";
		
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
				
				<li><a href="{{route('admin.news.edit',$item->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit this news item</a></li>
				<li><a data-href="{{route('admin.news.delete',$item->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this news item and all its comments" href="#">
                <i class="glyphicon glyphicon-trash"></i> Delete this news item</a></li>
			  </ul>
			</div>
		
		<?php

		break;	
		
		
		default:
			echo $item->$field;
		break;
		
	} 
	
    echo "</td>";
	 }?>
    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')



@stop