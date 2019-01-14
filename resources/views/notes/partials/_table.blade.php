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
        @if($note->relatesToLocation)
        <tr>  
    	<td>
         @if(isset($note->relatesToLocation->company))
            <a href ="{{route('notes.company',$note->relatesToLocation->company->id)}}"
            title = "See all {{$note->relatesToLocation->company->companyname}} location notes">
            {{$note->relatesToLocation->company->companyname}}
            </a>
         @endif

        </td>
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
        @endif
   @endforeach
    
    </tbody>
    </table>