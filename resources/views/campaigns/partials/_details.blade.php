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

@foreach ($data['branches'] as $branch)

    <tr>
    <td><a href="{{route('branchcampaign.show', [$campaign->id, $branch->id])}}">{{$branch->id}}</a></td>
    <td>{{$branch->branchname}}</td>
    <td class="text-right">
        @if(isset($data['locations']['assignable'][$branch->id]))
            @php isset($assignable) ?  $assignable = $assignable + $data['locations']['assignable'][$branch->id] : $assignable = $data['locations']['assignable'][$branch->id]  @endphp
                {{$data['locations']['assignable'][$branch->id]}}
        @endif
    </td>
     <td class="text-right">
        @php isset($totalleads) ?  $totalleads = $totalleads + $branch->workedleads : $totalleads = $branch->workedleads @endphp
       {{$branch->workedleads}}
    </td>
    
    
 </tr>

 @endforeach
 <tfoot>
    <td>Totals</td>
    <td>Unassignable:{{isset($assignable) ? $data['locations']['unassigned'] - $assignable : 0 }}</td>
    <td class="text-right">{{isset($assignable) ? $assignable : 0}}</td>
    
    <td class="text-right">{{isset($totalleads) ? $totalleads : 0}}</td>
</tfoot>
</tbody>
</table>
</div>
