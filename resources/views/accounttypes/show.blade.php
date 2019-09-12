@extends('admin/layouts/default')
@section('content')
<div class="page-header">


<h1> All {{$type->type}} Accounts</h1>
<a href="{{ route('accounttype.index') }}" title='show all account types'>All Account Types</a>
<table id="sorttable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Account</th>
            <th>Locations</th>
            <th>Locations Assigned to Branches</th>
            <th>Locations Worked</th>
            <th>Locations with Opportunities</th>
        </tr>
    </thead>
    <tbody>

    @foreach( $companies as $company)

        <tr>
            <td>
                <a href="{{ route('company.show', $company->id) }}" >
                    {{$company->companyname}}
                </a>
            </td>
            <td>{{$company->locations_count}}</td>
            <td>{{$company->leads}}</td>
            <td>{{$company->worked}}</td>
            <td>{{$company->opportunities}}</td>
            
        </tr>
    @endforeach

    </tbody>
</table>

@include('partials._scripts')

@endsection
