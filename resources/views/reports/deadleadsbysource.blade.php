<table>
    <thead>
        <tr></tr>
        <tr><th>Dead Leads by Branch and Source</th></tr>
        <tr><th>Leads (Excluding Branch Created Leads) created before {{$period['from']->format('Y-m-d')}} </th></tr>
        <tr><th>without Opportunities or Activities</th></tr>
        <tr></tr>
        <tr>
            <th><b>Branch</b></th>
            <th><b>Source</b></th>
            <th><b># Dead leads</b></th>
           
        </tr>
    </thead>
    <tbody>
        @foreach ($branches as $branch)
            
            <tr>
                <td>{{$branch->branchname}}</td>
                <td>{{$branch->source}}</td>
                <td>{{$branch->deadleads}}</td>
            </tr>
        @endforeach
    </tbody>

</table>