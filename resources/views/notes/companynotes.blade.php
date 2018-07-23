@extends('admin.layouts.default')
@section('content')
<div>
<h1>All {{$company->companyname}} Location Notes</h1>
<p><a href="{{route('notes.index')}}">See all notes</a></p>
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
   
    <th>Location Name</th>
    <th>Address</th>
    <th>Note</th>
    <th>Posted By</th>
    <th>Date</th>
   
       
    </thead>
    <tbody>
   @foreach($notes as $note)

    <tr>  
	
    <td>
        <a href="{{route('locations.show',$note->relatesToLocation->id)}}"
        title ="Review all notes at this  location" >
            {{$note->relatesToLocation->businessname}}
        </a>
    </td>
    <td>
    {{ucwords(strtolower($note->relatesToLocation->city))}}, {{strtoupper($note->relatesToLocation->state)}}
    </td>
    <td>{{$note->note}}</td>
    <td>
    @if(isset($note->writtenBy) && null!== $note->writtenBy->person)
        {{$note->writtenBy->person->fullName()}}
    @else
        No Longer with Company
    @endif
    </td>
    <td>{{$note->created_at->format('M j, Y')}}</td>
    </tr>
   @endforeach
    
    </tbody>
    </table>
    </div>
@include('partials/_scripts')
@stop