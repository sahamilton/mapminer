@extends('site/layouts/default')
@section('content')

<h1>{{$title}}</h1>
{{$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''}}

@include('partials/_showsearchoptions')
@include('partials/advancedsearch')
@include('partials.companyfilter')

@if (Auth::user()->hasRole('Admin'))
<?php $fields['Actions']='actions';?>
<div class="pull-right">
				<a href="{{{ URL::to('company/create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Account</a>
			</div>
    @endif

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @foreach($fields as $key=>$value)
    <th>
    {{$key}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($companies as $company)
   	@if(isset($locationFilter) && $locationFilter != 'both'  
   	&& (isset( $company->countlocations->first()->count) && $locationFilter=='nolocations') 
   	|| ! isset( $company->countlocations->first()->count) && $locationFilter=='locations') 
   		
   	@endif
   	
    <tr>  
	<?php reset($fields);?>
    @foreach($fields as $key=>$value)
    <td><?php 
	
		
	switch ($key) {
		case 'Company':
			$title = "See all ".$company->companyname." locations";
			if(isset( $company->countlocations->first()->count) &&  $company->countlocations->first()->count > 0)
			{
				echo "<a href=\"/company/".$company->id."\" title=\"".$title."\">".$company->companyname."</a>";
			}else{
				echo $company->companyname;
			}
			
		break;
		
		case 'Manager':
			if(isset($company->managedBy->firstname)){
				$name = $company->managedBy->firstname . " " . $company->managedBy->lastname;
				$title = "See all national accounts managed by ".$name;
				echo "<a href=\"/person/".$company->managedBy->id."\" title=\"".$title."\">".$name."</a>";
			}
		break;
		
		case 'Email':
		if(isset($company->managedBy->userdetails->email)){
			$name = $company->managedBy->firstname . " " . $company->managedBy->lastname;
			$title="Send email to ".$name;
			echo "<a href=\"mailto:".$company->managedBy->email."\" title=\"".$title."\">".$company->managedBy->userdetails->email."</a>";
		}
		break;
		
		case 'Vertical': 
			if(isset($company->industryVertical->filter)){
				$vertical = $company->industryVertical->filter;
				$title = "See all ". $vertical ." accounts.";
					echo "<a href=\"" . route('company.vertical',$company->industryVertical->id,'co'). "\" title=\"".$title."\">".$vertical."</a>";
			}else{
				echo "Not Assigned";	
			}
		break;
		
		case 'Service Lines':
		echo "<ul>";
			foreach ($company->serviceline as $serviceline)
			{
				echo "<li><a href ='/serviceline/". $serviceline->id."/co' >". $serviceline->ServiceLine . "</a></li>";

			}
			echo "</ul>";
			

		break;

		case 'Has Locations':
			if(isset( $company->countlocations->first()->count))
			{
				echo "Yes";
			}else{
				echo "No";
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
				
				<li><a href="/company/{{$company->id}}/edit/"><i class="glyphicon glyphicon-pencil"></i> Edit {{$company->companyname}}</a></li>
				<li><a data-href="/company/{{$company->id}}/delete" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$company->companyname}} and all its locations" href="#"><i class="glyphicon glyphicon-trash"></i> Delete {{$company->companyname}}</a></li>
			  </ul>
			</div>
		
		<?php
		
		
		break;
		
		
		
		
		default:
			echo $company->$field;
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