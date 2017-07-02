

<table>
 <tbody>
     <tr>
     <td>companyid</td>
     <td>companyname</td>
     <td>locationid</td>
     <td>businessname</td>
     <td>date</td>
     <td>note</td>

     <td>person</td>
    
    </tr>
 
   @foreach($notes as $note)
    <tr>  
	
    <td>{{$note->relatesTo->company_id}}</td>
    <td>{{$note->relatesTo->company->companyname}}</td>
    <td>{{$note->location_id}}</td>
    <td>{{$note->relatesTo->businessname}}</td>
    <td>{{$note->created_at}}</td>
    <td>{{$note->note}}</td>

    <td>
    @if(isset($note->writtenBy))

    {{$note->writtenBy->fullname()}}
    @else
    No longer in system
     @endif
     </td>
    </tr>
   @endforeach
    
    </tbody>
    </table>

