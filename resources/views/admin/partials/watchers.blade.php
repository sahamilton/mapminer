<div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
<h4>Active Watchers</h4>
<table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Users
    </th>
   <th>
   Watching Locations
   </th>
       
    </thead>
    <tbody>
   @foreach($data['watchlists'] as $watchers)
<tr>
<td>
<a href="/admin/watching/{{$watchers['user_id']}}" >{{$watchers['name']}}</a>
</td>
<td>
{{$watchers['watching']}}
</td>

 </tr>
 
    @endforeach
    </tbody>
    </table>


</div>