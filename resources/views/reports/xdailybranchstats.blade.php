<table>
    <thead>
        <tr></tr>
        <tr><th colspan="7">Daily Branch Statistics</th></tr>
        <tr><th colspan="7">for {{$person->fullName()}}</th></tr>
        <tr><th colspan="7">For {{$period['from']->format('M jS,Y')}}</th></tr>
        <tr></tr>
        <tr>
            <th><b>Branch Name</b></th>
            <th><b>Branch ID</b></th>
            <th><b>Branch Manager</b></th>
            <th><b>Reports To</b></th>
            <th><b># New Leads Created</b></th>
            <th><b># Proposals Completed</b></th>
            <th><b># Site Visits Completed</b></th>
            <th><b># Sales Appointments Completed</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($branches as $branch)
 
            <tr>
                <td>{{$branch->branchname}}</td>
                <td>{{$branch->id}}</td>
                <td>
                    @foreach ($branch->manager as $manager)
                    {{$manager->fullName()}}
                    @if(! $loop->last):@endif
                    @endforeach
                </td>
                <td>
                    @foreach ($branch->manager as $manager)
                    @if($manager->reportsTo)
                       {{ $manager->reportsTo->fullName()}}
                    
                    @if(! $loop->last):@endif
                    @endif
                    @endforeach
                </td>
                <td>{{$branch->newbranchleads}}</td>
                <td>{{$branch->proposals}}</td>
                <td>{{$branch->sitevisits}}</td>
                <td>{{$branch->salesappts}}</td>
                

            </tr>
       @endforeach
    </tbody>
</table>