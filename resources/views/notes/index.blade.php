@extends('site.layouts.default')
@section('content')
<div>
<h1>All Location Notes</h1>
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>Company</th>
    <th>Location Name</th>
    <th>Address</th>
    <th>Note</th>
    <th>Posted By</th>
    <th>Date</th>
   
       
    </thead>
    <tbody>
   @foreach($notes as $note)
        @if($note->relatesTo)
        <tr>  
    	<td>
         @if(isset($note->relatesTo->company))
            <a href ="{{route('notes.company',$note->relatesTo->company->id)}}"
            title = "See all {{$note->relatesTo->company->companyname}} location notes">
            {{$note->relatesTo->company->companyname}}
            </a>
         @endif

        </td>
        <td>
            <a href="{{route('locations.show',$note->relatesTo->id)}}"
            title ="Review all notes at this  location" >
                {{$note->relatesTo->businessname}}
            </a>
        </td>
        <td>
        {{ucfirst(strtolower($note->relatesTo->city))}}, {{strtoupper($note->relatesTo->state)}}
        </td>
        <td>{{$note->note}}</td>
        <td>
        @if(isset($note->writtenBy) && null!== $note->writtenBy->person)
            {{$note->writtenBy->person->postName()}}
        @else
            No Longer with Company
        @endif
        </td>
        <td>{{$note->created_at->format('M j, Y')}}</td>
        </tr>
        @endif
   @endforeach
    
    </tbody>
    </table>
    </div>
@include('partials/_scripts')
@stop