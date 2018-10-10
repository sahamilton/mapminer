@extends('site.layouts.default')
@section('content')
<h2>{{$data['title']}}</h2>
<p><a href="{{route('managers.view')}}">Back to account manager views</a></p>
<p>
<a href="{{route('exportlocationnotes',$companyID)}}" title="Download {{$data['title']}} as a CSV / Excel file"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> Download {{$data['title']}}</a>
</p>

 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
     <th>Location Name</th>
     <th>Address</th>
     <th>Note</th>
     <th>Posted By</th>
     <th>Posted</th>
       
    </thead>
    <tbody>
   @foreach($notes as $note)
    <tr>  
	
    <td>
	    <a href="{{route('locations.show',$note->relatesToLocation->id)}}"
        title="See all details of the {{$note->relatesToLocation->businessname}} location">
			{{$note->relatesToLocation->businessname}}
		</a>
	</td>
 <td>
 {{ucwords(strtolower($note->relatesToLocation->city))}}, {{strtoupper($note->relatesToLocation->state)}}</td>
	<td>{{$note->note}}</td>

	<td>
    @if(isset($note->writtenBy))

    {{$note->writtenBy->fullname()}}
    @else
    No longer in system
     @endif
    </td>
	<td>{{$note->created_at->format('M j, Y')}}</td>

    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials._scripts')


@endsection
