<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		
		<th>type</th>
            <th>firm</th>
            <th>contact</th>
            <th>title</th>
            <th>addr1</th>
            <th>addr2</th>
            <th>city</th>
            <th>state</th>
            <th>zipcode</th>
            <th>phone</th>

	</thead>
	<tbody>
            @foreach ($project->companies as $company)
                  <tr>
                        <td>{{$company->pivot->type}}</td>
                        <td><a href="{{route('projectcompany.show',$company->id)}}"
                        title="See all {{$company->firm}} construction projects">
                        {{$company->firm}}</a></td>
                        <td>

                        @if(! null==$company->employee()->first())
                              {{$company->employee()->first()->contact}}
                              </td>
                              <td>{{$company->employee()->first()->title}}
                        @else
                              </td><td>
                        @endif
                        </td>
                        <td>{{$company->addr1}}</td>
                        <td>{{$company->addr2}}</td>
                        <td>{{$company->city}}</td>
                        <td>{{$company->state}}</td>
                        <td>{{$company->zipcode}}</td>
                        <td>{{$company->phone}}</td>
                  </tr>
            @endforeach
      </tbody>
</table>