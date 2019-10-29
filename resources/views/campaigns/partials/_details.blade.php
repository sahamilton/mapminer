<div class="col-sm-6" >
    @php $total = 0; @endphp

<table id="sorttable"
name="branches"
>
<thead>
    <th>ID</th>
    <th>Branch</th>
    <th>Assignable Leads</th>
    <th>Current Campaign Leads</th>
</thead>
<tbody>
@foreach ($data['assignments']['branch'] as $branchid=>$leads)
    @if ($data['branches']->where('id', $branchid)->count())
    @php $branch = $data['branches']->where('id', $branchid)->first() @endphp
    @else
    @php $branch = null @endphp
    @endif
    <tr>
    <td>{{$branchid}}</td>
    <td>@if ($branch)
        {{$branch->branchname}}
        @endif
    </td>
    <td class="text-right">
        @php $total = $total + count($leads) @endphp
        {{count($leads)}}
    </td>
     <td class="text-right">
     @if ($branch)
        {{$branch->leads_count}}
    @else
        0
    @endif
    </td>
 </tr>
 @endforeach
 <tfoot>
    <td>Totals</td>
    <td>Unassignable {{count($data['assignments']['unassigned'])}}</td>
    <td class="text-right">{{$total}}</td>
    <td class="text-right">{{$data['branches']->sum('leads_count')}}</td>
</tfoot>
</tbody>
</table>
</div>
