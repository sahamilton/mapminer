<table>
    <thead>
        <tr></tr>
        <tr>
            <th>{{$campaign->title}}</th>
        </tr>
        <tr></tr>
        <tr>
            <th>Branch</th>
            <th>Branch Name</th>
            <th>Offered Leads</th>
            <th>Worked Leads</th>
            <th>Activities</th>
            <th>New Opportunities</th>
            <th>Opportunities Open</th>
            <th>Opportunities Won</th>
            <th>Opportunities Lost</th>
            <th>Opportunities Won Value</th>
            <th>Opportunities Open Value</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($branches as $branch)
   
       
        <tr>
            <td>{{$branch->id}}</td>
            <td>{{$branch->branchname}}</td>
            <td>{{$branch->offered_leads_count}}</td>
            <td>{{$branch->leads_count}}</td>
            <td>{{$branch->activities_count}}</td>
            <td>{{$branch->opened}}</td>
            <td>{{$branch->open}} </td>
            <td>{{$branch->won}}</td>
            <td>{{$branch->lost}}</td>
            <td>${{number_format($branch->wonvalue,2)}}</td>
            <td>${{number_format($branch->openvalue,2)}}</td>
        </tr>
        @endforeach
    </tbody>
    
</table>