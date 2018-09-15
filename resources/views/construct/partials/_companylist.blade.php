<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Type</th>
            <th>Firm</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>
            <th>Phone</th>
            <th>EMail</th>

	</thead>
	<tbody>
            @foreach ($project['companylinks'] as $company)
                  <tr>
                        <td><a href="{{$company['company']['id']}}">{{$company['companylinktype']}}</td>
                        <td>{{$company['company']['name']}}</a></td>
                         
                        <td>{{$company['company']['address']}}</td>
                        <td>{{$company['company']['city']}}</td>
                        <td>{{$company['company']['state']}}</td>
                        <td>{{$company['company']['zip']}}</td>
                        <td>{{$company['company']['phone']}}</td>
                        <td>{{$company['company']['email']}}</td>
                  </tr>
            @endforeach
      </tbody>
</table>


</div>
