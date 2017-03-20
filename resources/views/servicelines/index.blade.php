@extends('admin/layouts/default')
@section('content')

<h1>Service Lines</h1>

@if (Auth::user()->hasRole('Admin'))
<?php $fields['Actions']='actions';?>
<div class="pull-right">
				<p><a href="{{{ route('serviceline.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Service Line</a></p>
			</div>
    @endif

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @foreach($fields as $key=>$field)
    <th>
    {{$key}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($servicelines as $serviceline)

    <tr>  
	<?php reset($fields);?>
    @foreach($fields as $key=>$field)
    <td><?php 
	
		
	switch ($key) {
		
		case 'Actions':
		
			?>
            @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="{{route('serviceline.edit',$serviceline->id)}}/"><i class="glyphicon glyphicon-pencil"></i> 
				Edit {{$serviceline->ServiceLine}}</a></li>
				<li><a data-href="/admin/serviceline/{{$serviceline->id}}/delete" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$serviceline->ServiceLine}} and all its associations" href="#"><i class="glyphicon glyphicon-trash"></i> Delete {{$serviceline->ServiceLine}}</a></li>
			  </ul>
			</div>
		
		<?php
		
		
		break;

		case 'Branches':
			if(isset($serviceline->branchCount[0])){
				echo "<a href ='/serviceline/". $serviceline->id."'";
				echo " title = 'See all ". $serviceline->ServiceLine ." Branches '>";
				echo $serviceline->branchCount[0]['aggregate']. "</a>";
				
			}else{
				echo '0';
			}
			
		break;
		
		case 'Companies':
			if(isset($serviceline->companyCount[0])){
				echo "<a href ='/serviceline/". $serviceline->id."/co' ";
				echo " title = 'See all ". $serviceline->ServiceLine ." Companies '>";
				echo $serviceline->companyCount[0]['aggregate']. "</a>";
				
			}else{
				echo '0';
			}
			
		break;

		case 'Users':
			if(isset($serviceline->userCount[0])){
				echo "<a href='". route('serviceline.user',$serviceline->id). "'";
				echo " title = 'See all ". $serviceline->ServiceLine ." users '>";
				echo  $serviceline->userCount[0]['aggregate']. "</a>";
			}else{
				echo '0';
			}
			
		break;
		
		


		default:
		
			
			echo "<img src='https://maps.google.com/mapfiles/ms/icons/". $serviceline->color."-dot.png' /> ";
			echo $serviceline->$field;
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