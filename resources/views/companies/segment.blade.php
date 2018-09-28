@extends('site.layouts.default')
@section('content')
<?php $company = $data ['company'];
$data['type']='company';
$data['company'] = $company->id;
$data['companyname']=$company->companyname;
?>

<h2>All {{$company->companyname}} {{$data['segment']}} Locations </h2>
@if($limited)
	@include('companies.partials._limited')
@endif
@include('companies.partials._segment')


<p><a href="{{ route('company.show', $company->id) }}" title='Show all {{$company->companyname}} Locations'>All {{$company->companyname}} Locations</a></p>


@include('maps.partials._form')
@include('companies.partials._state')
@include('partials.advancedsearch')
@if(auth()->user()->hasRole('Admin'))
<div class="pull-right">
				<a href="{{{ route('locations.create') }}}" class="btn btn-small btn-info iframe">
<i class="fa fa-plus-circle " aria-hidden="true"></i>
 Create</a>
			</div>
			@endif
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
	     <th>Business Name</th>
	     <th>Street</th>
	     <th>City</th>
	     <th>ZIP</th>
	     <th>Contact</th>
	     <th>Phone</th>
	     <th>Watching</th>
	     @if(auth()->user()->hasRole('Admin'))
	     	<th>Actions</th>
	     @endif
    </thead>
    <tbody>
   @foreach($locations as $location)
    <tr>  
	<td>
		<a href="{{route('locations.show',$location->id)}}"
		 title="See details of the {{$location->businessname}} location."\">
		 {{$location->businessname}}
	 	</a>
	</td>
	<td>{{$location->street}}</td>
	<td>{{$location->city}}</td>
	<td>{{$location->zip}}</td>
	<td>{{$location->contact}}</td>
	<td>{{$location->phone}}</td>

	<td style ="text-align: center; vertical-align: middle;">
			
		<input {{in_array($location->id,$mywatchlist) ? 'checked' : ''}}
		 id="{{$location->id}}"
		 type='checkbox' name='watchList' class='watchItem'  
		 value='{{$location->id}}' >
	</td>
	@if(auth()->user()->hasRole('Admin'))
		<td>
			@include('partials/_modal')

			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<a class="dropdown-item"
					href="{{route('locations.edit',$location->id)}}">
					<i class="fa fa-pencil text-info" 
					aria-hidden="true"> </i>
					Edit {{$location->businessname}}</a>
					<a class="dropdown-item"
					  data-href="{{route('locations.destroy',$location->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$location->businessname}} and all associated notes" href="#"><i class="fa fa-trash-o text-danger" aria-hidden="true"> </i> 
					Delete {{$location->businessname}}</a>
				</ul>
			</div>
		</td>

	@endif
    
	
    </tr>
   @endforeach
    
    </tbody>
    </table>
    </div>

@include('partials/_scripts')
@endsection