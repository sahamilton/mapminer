@extends('site/layouts/default')
@section('content')

<?php $company = $data ['company'];
$data['type']='company';
$data['company'] = $company->id;
$data['companyname']=$company->companyname;
?>

<h2>All {{$company->companyname}} Locations in {{$data['state']}}</h2>
{!!$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}

@include('companies/partials/segment')
<p><a href="{{ URL::to('company/'. $company->id) }}" title='Show all {{$company->companyname}} Locations'>All {{$company->companyname}} Locations</a></p>

<?php $data['address'] = "Lat:" .number_format($data['lat'],3) . "  Lng:" .number_format($data['lng'],3) ;
$data['distance'] = Config::get('default_radius');?>
@include('maps/partials/_form')
@include('companies/partials/_state')
@include('partials/advancedsearch')
<div class="pull-right">
				<a href="{{{ URL::to('location/create/'.$company->id) }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create</a>
			</div>
	    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
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
		
		case 'Segment':
			echo "<td>";
			

				echo $location->segment ? "<a href=\"/company/".$data['company']."/segment/".$location->segment."\">". $filters[$location->segment] . "</a>" : 'Not Specified';
			echo "</td>";
		break;
		
		case 'Business Type':
			echo "<td>";
				echo $location->businesstype ? $filters[$location->businesstype] : 'Not Specified';
			echo "</td>";
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
    </table>
    </div>
@include('partials/_scripts')
@stop