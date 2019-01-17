@extends('site.layouts.default')
@section('content')

<?php $company = $data ['company'];
$data['type']='company';
$data['company'] = $company->id;
$data['companyname']=$company->companyname;
?>

<h2>All {{$company->companyname}} Locations in {{$data['state']}}</h2>

@include('companies.partials._segment')
<p><a href="{{ route('company.show', $company->id) }}" title='Show all {{$company->companyname}} Locations'>All {{$company->companyname}} Locations</a></p>

<?php $data['address'] = "Lat:" .number_format($data['lat'],3) . "  Lng:" .number_format($data['lng'],3) ;
$data['distance'] = Config::get('default_radius');?>
@include('maps/partials/_form')

@include('companies/partials/_state')
@include('partials/advancedsearch')
@if(auth()->user()->hasRole('admin'))

@endif

@include('companies.partials._table')

    </div>
@include('partials/_scripts')
@endsection
