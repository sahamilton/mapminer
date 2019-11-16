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
   @if(! ($branch->leads_count == 0 && ! array_key_exists($branch->id, $data['assignments']['branch'])))
    <tr>
    <td><a href="{{route('branchcampaign.show', [$campaign->id, $branch->id])}}">{{$branch->id}}</a></td>
    <td>{{$branch->branchname}}</td>
    <td class="text-right">
        @if(array_key_exists($branch->id, $data['assignments']['branch']))

            @php 
             $assignable = count($data['assignments']['branch'][$branch->id]); 
             $total = $total + $assignable 
             @endphp
        
        {{$assignable}}
        @else
        0
        @endif
    </td>
     <td class="text-right">
        @if (isset($data['branchesw'][$branch->id]))
            {{$data['branchesw'][$branch->id]->count()}}
            @php
            $totalleads = isset($totalleads) ? $totalleads + $data['branchesw'][$branch->id]->count() : $data['branchesw'][$branch->id]->count()
            @endphp
        @else
            0
        @endif
    </td>
    
    
 </tr>
 @endif
 @endforeach
 <tfoot>
    <td>Totals</td>
    <td>Unassignable {{count($data['assignments']['unassigned'])}}</td>
    <td class="text-right">{{isset($total) ? $total : 0}}</td>
    <td class="text-right">{{isset($totalleads) ? $totalleads : 0}}</td>
</tfoot>
</tbody>
</table>
</div>
