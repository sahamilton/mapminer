<table>
    <thead>
        <tr><th colspan="8"><h2>Branch Activities Detail</h2></th></tr>
        <tr><th colspan="8"><h4>For the period from {{$period['from']->format('M jS,Y')}} to {{$period['to']->format('M jS,Y')}}</h4></th></tr>
        <tr></tr>
        <tr>
            <th><b>Branch</b></th>
            <th><b>Manager</b></th>
            <th><b>Week beginning</b></th>
            <th><b>Activity</b></th>
            <th><b>Count</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $branch)
        
            <tr>
                <td>{{$branch->branchname}}</td>
                <td>{{$branch->manager}}</td>
                <td>{{$branch->weekbegin}}</td>
                <td>{{$branch->activity}}</td>
                <td>{{$branch->activitycount}}</td>
            </tr>
        @endforeach
    </tbody>

</table>
