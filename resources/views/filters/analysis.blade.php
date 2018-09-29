@extends('admin/layouts/default')
@section('content')
<div class="container">
<h2>Industry Vertical Analysis</h2>
<p><a href =" {{{ route('vertical.export') }}}">
<i class="far fa-cloud-download" aria-hidden="true"></i> Export Table to Excel</a></p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    
    <th>Parent</th>
    <th>Vertical</th>
    <th>People</th>
    <th>Current Prospects</th>
    <th>Companies</th>
    <th>Locations</th>
    <th>Segment Locations</th>
    <th>Current Campaigns</th>
      
       
    </thead>
    <tbody>

 @include('filters.partials._table')
    
    </tbody>
</table>
</div>
@include('partials/_scripts')
@endsection