@extends('site/layouts/default')
@section('content')
<?php $account = Request::segment(2);


?>
<div id='results'></div>

<div>
<h3>Locations for {{$company->companyname}}</h3>

{{$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''}}
@if (isset($company->industryVertical->filter))
<p>{{$company->industryVertical->filter}} Vertical</p>
@endif
<h4>ServiceLines:</h4>
<ul>
@foreach($company->serviceline as $serviceline)
<li>{{$serviceline->ServiceLine}} </li>
@endforeach
</ul>



@include('companies/partials/segment')






@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->firstname.' '.$company->managedBy->lastname}}">{{$company->managedBy->firstname.' '.$company->managedBy->lastname}}</a></p>
@endif
@if (Auth::user()->hasRole('Admin'))

<div class="pull-right" style="margin-bottom:20px">
				<a href="{{{ URL::to('location/create/'.$account) }}}" title="Create a new {{$company->companyname}} location" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Location</a>
			</div>
           @endif
         
<p><a href="{{route('salesnotes',$company->id)}}" title="Read notes on selling to {{$company->companyname}}"><i class="glyphicon glyphicon-search"></i>  Read 'How to Sell to {{$company->companyname}}'</a>
<a href="/watch" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a>
<a href="/watchexport" title="Download my watch list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Watch List</a>
<a href="{{route('exportlocationnotes',$company->id)}}" title="Download my {{$company->companyname}} location notes as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download my {{$company->companyname}} location notes</a> </p>
<p><a href="{{ URL::to('company') }}" title='show all accounts'><i class="glyphicon glyphicon-th-list"></i> All Accounts</a></p>
@include('partials/advancedsearch')
 
@include('companies/partials/_state')
@include('maps.partials._form')

   <p style="background-color:yellow">There are <strong>{{$count}}</strong> locations for  {{$company->companyname}}. Please select a state to narrow your search</p>
   <p style="background-color:yellow">Here are the 500 closest to your location</p>
<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @foreach($fields as $title=>$field)
    <th>
    {{$title}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($locations as $location)

    <tr>  
	<?php reset($fields);?>
    @foreach($fields as $title=>$field)
    <?php 
	
	switch ($title) {
		case 'Business Name':
			$title = "See details of the ".$location->$field." location";
			echo "<td><a href=\"/location/".$location->id."\"";
			echo " title=\"".$title."\">".$location->$field."</a></td>";
		break;
		
		case 'State':
			echo "<td><a href =\"".route('company.state', array('companyId'=>$company->id,'state'=>$location->$field))."\">". $location->$field."</a></td>";
		
		break;
		
		case 'Watching':
			echo "<td style =\"text-align: center; vertical-align: middle;\">";
			
			if(in_array($location->id,$mywatchlist)){
				echo "<input checked";
				
			}else{
				echo "<input ";
			}
			echo " id=\"".$location->id."\" ";
			echo " type='checkbox' name='watchList' class='watchItem' ";
			echo " value='".$location->id."' ></td>";
		break;
		
		case 'Segment':
		
			echo  empty($location->segment) ?  "<td>Not Specified</td>": "<td><a href=\"/company/".$company->id."/segment/".$location->segment."\">". $filters[$location->segment] . "</a></td>";
		
		break;
		
		case 'Business Type':
			echo empty($location->businesstype) ?  "<td>Not Specified</td>" : "<td>". $filters[$location->businesstype] . "</td>";
		
		break;
		
		
		
		case 'Actions':
			echo "<td>";
			?>
            @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="/location/{{$location->id}}/edit/"><i class="glyphicon glyphicon-pencil"></i> Edit {{$location->businessname}}</a></li>
				<li><a data-href="/location/{{$location->id}}/delete" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$location->businessname}} and all associated notes" href="#"><i class="glyphicon glyphicon-trash"></i> Delete {{$location->businessname}}</a></li>
			  </ul>
			</div>
		
		<?php
			echo "</td>";
		break;	
		
		default:
			echo "<td>".$location->$field."</td>";
		break;
		
	};?>
	

    @endforeach
    </tr>
   @endforeach
    
    </tbody>
    </table>@include('partials/_scripts')
@stop

