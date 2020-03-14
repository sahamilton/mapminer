<table>
    <thead>
        <th>Branch</th>
        <th>Stale Leads Count</th>
    </thead>
    <tbody>
        @foreach ($branches as $branch)
            <tr>
                <td>{{$branch->branchname}}</td>
                <td>{{$branch->stale_leads_count}}</td>
            </tr>
        @endforeach
    </tbody>
</table>