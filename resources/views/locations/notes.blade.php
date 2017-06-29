@extends('site.layouts.default')
@section('content')
<div>
<h1>All Location Notes</h1>
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>Company</th>
    <th>Location Name</th>
    <th>Note</th>
    <th>Posted By</th>
    <th>Date</th>
   
       
    </thead>
    <tbody>
   @foreach($notes as $note)

    <tr>  
	<td>
     @if(isset($note->relatesTo->company))
        {{$note->relatesTo->company->companyname}}
     @endif

    </td>
    <td>
        <a href="{{route('locations.show',$note->relatesTo->id)}}"
        title ="Review all notes at this  location" >
            {{$note->relatesTo->businessname}}
        </a>
    </td>
    <td>{{$note->note}}</td>
    <td>
    @if(isset($note->writtenBy->person))
        {{$note->writtenBy->person->postName()}}
    @else
        {{$note->writtenBy}}
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