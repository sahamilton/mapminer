<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Firm</th>
            <th>Type</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>
            <th>Phone</th>
            

	</thead>
	<tbody>
            @foreach ($project['companylinks'] as $company)
  
                  <tr>
                        <td><a href="{{route('construction.company',$company['company']['id'])}}">{{$company['company']['name']}}</a></td>
                        <td>{{$company['companylinktype']}}</td>
                         
                        <td>{{$company['company']['address']}}</td>
                        <td>{{$company['company']['city']}}</td>
                        <td>{{$company['company']['state']}}</td>
                        <td>{{$company['company']['zip']}}</td>
                        <td>{{$company['company']['phone']}}</td>
                       
                  </tr>
            @endforeach
      </tbody>
</table>
