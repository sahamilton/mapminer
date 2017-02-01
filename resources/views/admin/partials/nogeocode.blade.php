<div style="border:1px solid #000;width:500px;margin:20px;padding:20px;float:left">
<h4>Locations That Can't Be Coded</h4>
<table id ='sorttable1'  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   <th>
   Street
   </th>
    <th>
   City
   </th>
       <th>
  State
   </th>
          <th>
  ZIP
   </th>
       
    </thead>
    <tbody>
   @foreach($data['nogeocode'] as $nogeocode)
<tr>
<td>
<a href = "{{url('/location/'.$nogeocode->id.'/edit/')}}">{{$nogeocode->company->companyname}}</a>
</td>
<td>
{{$nogeocode->street}}
</td>
<td>
{{$nogeocode->city}}
</td>
<td>
{{$nogeocode->state}}
</td>
<td>
{{$nogeocode->zip}}
</td>

 </tr>
 
    @endforeach
    </tbody>
    </table>


</div>