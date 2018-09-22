<div style="clear:both;border:1px solid #000;width:340px;padding:20px;margin:20px;float:left">
<h4>Companies without SalesNotes</h4>
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   
       
    </thead>
    <tbody>
   @foreach($data['nosalesnotes'] as $company)
    <tr class="danger">  
	
    
    <td><a href="{{route('salesnotes.cocreate',$company->id)}}">{{$company->companyname}}</a>
		
	
    </td>
    
    
    
    
    </tr>
 
    @endforeach
    </tbody>
    </table>

</div>