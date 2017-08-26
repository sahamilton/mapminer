<div style="border:1px solid #000;width:600px;margin:20px;padding:20px;float:left">
<h4>Lead Notes in past week</h4>
<!-- 'writtenBy','relatesTo','relatesTo.company','writtenBy.person' -->
<table id ='sorttable6' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>Company</th>
   <th>Business</th>
   <th>Address</th>
    <th>Note</th>
    <th>Date</th>
    <th>By</th>
         
    </thead>
    <tbody>

   @foreach($data['recentLeadNotes'] as $newNote)

<tr>

<td>{{$newNote->relatesToLead->companyname}}</td>
<td>
<a href = "{{route('leads.show',$newNote->relatesToLead->id)}}" title="Review {{$newNote->relatesToLead->businessname}} lead" >{{$newNote->relatesToLead->businessname}}</a>
</td>
<td>{{$newNote->relatesToLead->fullAddress()}}</td>
<td>{{$newNote->note}}</td>
<td>{{$newNote->created_at->format('jS M g:i A')}}</td>
<td>{{$newNote->writtenBy->person->postName()}}</td>


 </tr>

    @endforeach
    </tbody>
    </table>


</div>