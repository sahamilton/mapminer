<h4>Nearby Opportunities</h4>
<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Address</th>
        <th>Opportunity</th>
        <th>Value</th>
        <th>Expected Close</th>
    </thead>
    <tbody>
        @foreach($results as $result)
        
        <tr>
            <td>{{$result->address->address->businessname}}</td>
            <td>{{$result->address->address->fullAddress()}}</td>
            <td>{{$result->title}}</td>
            <td>{{$result->value}}</td>
            <td>{{$result->expected_close}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>