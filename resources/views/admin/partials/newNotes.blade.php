<div style="border:1px solid #000;width:700px;margin:20px;padding:20px;float:left">
<h4>Location Notes in past month</h4>
<!-- 'writtenBy','relatesTo','relatesTo.company','writtenBy.person' -->
<table id ='sorttable6' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>

    <th>Company</th>
    <th>Address</th>
    <th>Note</th>
    <th>Date</th>
    <th>By</th>
     
  </thead>
  <tbody>
   @foreach($data['recentLocationNotes'] as $newNote)

<tr>

  
  <td>
    <a href = "{{route('address.show',$newNote->address_id)}}" 
      title="Review {{$newNote->relatesToLocation->businessname}} location" >{{$newNote->relatesToLocation->businessname}} 
    </a>
  </td>
  <td>{{$newNote->relatesToLocation->fullAddress()}}</td>
  <td>{{$newNote->note}}</td>
  <td>{{$newNote->created_at ? $newNote->created_at->format('jS M g:i A'):''}}</td>
  <td>
    @if($newNote->writtenBy)
    {{$newNote->writtenBy->person->fullName()}}</td>
    @endif


 </tr>

    @endforeach
    </tbody>
    </table>


</div>