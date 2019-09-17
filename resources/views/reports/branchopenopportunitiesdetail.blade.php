<table>
    <thead>
        <tr></tr>
        <tr><th>Branch Open Detailed Opportunities</th></tr>
        <tr><th>As of {{$period['to']->format('M jS,Y')}}</th></tr>
        <tr>
            <th>Branch ID</th>
            <th>Branch Name</th>
            <th>Branch Manager</th>
            <th>Company</th>
            <th>Opportunity</th>
            <th>Requirements</th>
            <th>Duration</th>
            <th>Value</th>
            <th>Created</th>
            <th>Expected Close</th>
            <th>Current Status</th>
            <th>Days Open</th>
            <th>Actual Close</th>

    </thead>
    <tbody>
        @foreach($branches as $branch)
       
            @foreach ($branch->opportunities as $opportunity)
            <tr>
                <td>{{$branch->id}}</td>
                <td>{{$branch->branchname}}</td>
                <td>
                    @foreach ($branch->manager as $manager)
                        {{$manager->fullName()}}
                    @endforeach
                </td>
                <td>{{$opportunity->address->address->businessname}}</td>
                <td>{{$opportunity->title}}</td>
                <td>{{$opportunity->requirements}}</td>
                <td>{{$opportunity->duration}}</td>
                <td>${{$opportunity->value}}</td>
                <td>{{$opportunity->created_at->format('Y-m-d')}}</td>
                <td>
                    @if($opportunity->expected_close)
                        {{$opportunity->expected_close->format('Y-m-d')}}
                    @endif
                </td>
                <td>{{$opportunity->status()}}</td>
                <td>{{$opportunity->daysOpen()}}</td>
                <td>
                    @if($opportunity->actual_close)
                        {{$opportunity->actual_close->format('Y-m-d')}}
                    @endif
                </td>
            </tr>
            @endforeach
   
        @endforeach
    </tbody>
</table>



