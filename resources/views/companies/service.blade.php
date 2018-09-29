@extends('site/layouts/default')
@section('content')

<div id='results'></div>

<div>
<h4> {{$company->companyname}} 
@if (isset($data['statecode']))
{{strtoupper($data['statecode'])}}
@endif
Locations Serviced By
</h4>

<p><a href="{{route('company.service',$company->id)}}">
	Return to all locations of {{$company->companyname}}</a></p>
	<p>
@if(file_exists(storage_path('app/public/exports/'.strtolower(str_replace("'","",str_replace(" ", "_", $company->companyname))).".csv")))
 <a href="{{asset('/storage/exports/'.$company->companyname.'.csv')}}" target="_blank" title="Open service list as a CSV / Excel file"><i class="far fa-cloud-download" aria-hidden="true"></i></i> Open Service List</a>
  <a href="{{route('company.service.export',[$company->id,$data['statecode']])}}" title="Reload service list as a CSV / Excel file"><i class="far fa-refresh" aria-hidden="true"></i></a>
@else
 <a href="{{route('company.service.export',[$company->id,$data['statecode']])}}" title="Download service list as a CSV / Excel file"><i class="far fa-cloud-download" aria-hidden="true"></i></i> Export this Service List</a>
@endif
</p>
@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif

@include('companies.partials._limited')
<?php $route = 'company.service.select';?>
@include('companies.partials._state')
@include('companies.partials._servicetable')
@include('partials/_scripts')
@endsection

