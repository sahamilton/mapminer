<div>
    
    <table>

        <thead>
            
            <td>Company</td>
            <td>Account Type</td>
            <td>Industry</td>
            <td>Locations</td>
            <td>Assigned to Branches</td>
            <td>Period Activities</td>
            <td>Period Opportunities</td>
        </thead>
        <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>{{$company->companyname}}</td>
                    <td>{{$company->accounttype->type}}</td>
                    <td>{{$company->industryVertical->filter}}</td>
                    <td>{{$company->locations_count}}</td>
                    <td>{{$company->assigned}}</td>
                    <td>{{$company->activities_count}}</td>
                    <td>{{$company->opportunities_count}}</td>

                </tr>


            @endforeach

        </tbody>

    </table>
</div>
