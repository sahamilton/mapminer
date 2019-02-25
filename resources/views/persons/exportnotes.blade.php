

<table>
 <tbody>
     <thead>
     <th>companyid</th>
     <th>companyname</th>
     <th>locationid</th>
     <th>businessname</th>
     <th>date</th>
     <th>note</th>

     <th>person</th>
    
    </thead>
 <tbody>
   @foreach($notes as $note)
    <tr>  

    <td>{{$note->relatesToLocation->company_id}}</td>
    <td>{{$note->relatesToLocation->company->companyname}}</td>
    <td>{{$note->location_id}}</td>
    <td>{{$note->relatesToLocation->businessname}}</td>
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

