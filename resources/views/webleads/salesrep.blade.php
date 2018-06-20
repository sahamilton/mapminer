@extends('site.layouts.default')
@section('content')

<h2>{{$person->postName()}}'s Web Leads</h2>


    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Date Added</th>
     <th>Company</th>
     <th>Address</th>
     <th>Rating</th>
     <th>Your Rating</th>
     <th>Status</th>
     <th>Industry</th>
     
   
       
    </thead>
    <tbody>
   @foreach($webleads as $lead)

    <tr> 
    <td>{{$lead->created_at->format('Y-m-d')}}</td> 
	<td><a href="{{route('webleads.salesshow',$lead->id)}}">{{$lead->company_name}}</a></td>
	<td>{{$lead->address}} {{$lead->city}}, {{$lead->state}} {{$lead->zip}}</td>
	
	<td>{{$lead->rating}}</td>
    <td class="text-right">{{$lead->salesteam->first()->pivot->rating}}</td>
	<td>{{$leadstatuses[$lead->salesteam->first()->pivot->status_id]}} </td>
	<td>{{$lead->industry}}</td>
	
 
    </tr>
   @endforeach
    
    </tbody>
    </table>
          @include('partials/_modal')
@include('partials/_scripts')
@stop