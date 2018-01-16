@extends('admin.layouts.default')
@section('content')

<h1>{{$leadstatus->status}} Prospect Statuses</h1>



    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Company</th>
    <th>City</th>
    <th>State</th>
    <th>Lead Source</th>
    <th>Ranking</th>
    <th>Lead Owner</th>
    </thead>
    <tbody>
  @foreach ($leadstatus->leads as $lead)
  <tr>
    <td>{{$lead->companyname}}</td>
    <td>{{$lead->city}}</td>
    <td>{{$lead->state}}</td>
    <td>{{$lead->leadsource->source}}</td>
    <td>{{$lead->pivot->rating}}</td>
    <td>
        @if(count($lead->ownedBy)>0)
        {{$lead->ownedBy->first()->fullName()}}
        @endif
    </td>
</tr>
  @endforeach
    
    </tbody>
    </table>
@include('partials._scripts')
@stop