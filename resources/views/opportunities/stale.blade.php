@foreach ($branches as $branch)
<tr>
    <td>{{$branch->branchname}}</td>
    <td>{{$branch->stale_opportunities_count}}</td>
</tr>
@endforeach