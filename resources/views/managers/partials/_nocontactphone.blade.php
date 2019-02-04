
<h4>Locations without Contact Phone</h4>
<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   <th>
   Locations
   </th>
    <th>
   Locations w/o Contact
   </th>
       <th>
   Percent of Total
   </th>
       
    </thead>
    <tbody>
   @foreach($data['nocontact'] as $nocontact)
   @if($nocontact->percent > '25')
   	<tr class="danger">
    @elseif ($nocontact->percent > '10')
    <tr class ="warning">
    @else
    <tr class ='success'>
  @endif
<td>
<a href="/company/{{$nocontact->company_id}}" >{{$nocontact->companyname}}</a>
</td>
<td>
{{$nocontact->addresses}}
</td>
<td>
{{$nocontact->nocontacts}}
</td>
<td>
{{number_format($nocontact->percent).'%'}}
</td>
 </tr>
 
    @endforeach
    </tbody>
    </table>


