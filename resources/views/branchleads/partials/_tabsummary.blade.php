 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Branch</th>
        <th>Manager</th>
        <th>Reports To</th>
        <th>Leads</th>
    </thead>
    <tbody>
         @foreach($branches as $branch)

            <tr>
                <td><a href="{{route('branchleads.show',$branch->id)}}">{{ $branch->branchname}} </a></td>
                <td>
                    @foreach ($branch->manager as $manager)
                        {{$manager->fullName()}}
                    @endforeach
                    </td>
                <td>
                    @if($branch->manager->count()>0 && $branch->manager->first()->reportsTo)
                        {{$branch->manager->first()->reportsTo->fullName()}}
                    @endif
                </td>
                <td>{{$branch->leads_count}}</td>
            </tr>
         @endforeach
    </tbody>
</table>
