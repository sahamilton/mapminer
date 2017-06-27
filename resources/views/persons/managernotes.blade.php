@extends('site.layouts.default')
@section('content')
<h2>{{$data['title']}}</h2>
<p>
<a href="{{route('exportlocationnotes',$companyID)}}" title="Download {{$data['title']}} as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download {{$data['title']}}</a>
</p>

 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
     <th>Location Name</th>
     <th>Note</th>
     <th>Posted By</th>
     <th>Posted</th>
       
    </thead>
    <tbody>
   @foreach($notes as $note)
    <tr>  
	
    <td>
	    <a href="{{route(
'locations.show'
,$note->locationid)}}">
			{{$note->businessname}}
		</a>
	</td>

	<td>{{$note->note}}</td>

	<td>{{$note->person}}</td>
	<td>{{date('M j, Y',strtotime($note->date))}}</td>

    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials._scripts')


@stop
