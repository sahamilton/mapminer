@extends('site/layouts/default')
@section('content')
<div class="page-header">
<?php
        
		$arraystuff= $accounttype->toArray();
		;
?>	

<h1> All {{$arraystuff[0]['type']}} Accounts </h1>
<a href="{{ route('company') }}" title='show all accountss'>All Accounts</a>
<table class="table table-striped table-bordered">
<thead><tr>
<th>Account</th>
<th>Account Type</th>
</tr>
</thead>
<tbody>
@foreach($accounttype as $record)
@foreach( $record['companies'] as $company)

<tr><td><a href="{{ route('show/company', $company->id) }}" >{{$company->companyname}}</a></td>
<td><a href="{{ route('update/company', $company->id) }}" class="btn btn-mini">@lang('button.edit')</a>
<a href="{{ route('delete/company', $company->id) }}" class="btn btn-mini btn-danger">@lang('button.delete')</a></td>
</tr>
@endforeach
@endforeach
</tbody>
</table>



@endsection