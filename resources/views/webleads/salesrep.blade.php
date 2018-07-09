@extends('site.layouts.default')
@section('content')

<h2>{{$person->postName()}}'s Leads</h2>
<p><a href="{{route('webleads.map')}}"><i class="fa fa-map" aria-hidden="true"></i> Map View</a>
 

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Date Added</th>
     <th>Company</th>
     <th>Address</th>
     <th>Rating</th>
     <th>Source</th>
     <th>Your Rating</th>
     <th>Status</th>

     
   
       
    </thead>
    <tbody>
   @foreach($leads as $lead)

    <tr> 
    <td>{{$lead->created_at ? $lead->created_at->format('j M, Y') : ''}}</td> 
	<td><a href="{{route('webleads.salesshow',$lead->id)}}">{{$lead->companyname}}</a></td>
	<td>{{$lead->address->address}} {{$lead->address->city}}, {{$lead->address->state}} {{$lead->address->zip}}</td>
	
	<td>{{$lead->rating}}</td>
    <td>{{$lead->leadsource()->first()->source}}</td>
    <td class="text-right">{{$lead->salesteam->first()->pivot->rating}}</td>
	<td>{{$leadstatuses[$lead->salesteam->first()->pivot->status_id]}} </td>

	
 
    </tr>
   @endforeach
    
    </tbody>
    </table>
          @include('partials/_modal')
@include('partials/_scripts')
@stop