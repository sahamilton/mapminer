


<h4>Companies with Location Notes</h4>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   <th>
  Notes
   </th>
       
    </thead>
    <tbody>
@foreach ($data['notes'] as $notes)

<tr>
<td>
<a href="{{route('locationnotes.show',$notes->id)}}">{{$notes->companyname}}</a>
</td>
<td>
{{$notes->notes}}</td>
</tr>


@endforeach
    </tbody>
    </table>





