<table id="sorttable"
    name="branchsummary"
    class="table table-striped"
    >
    <thead>
        <th>Branch</th>
        <th>Branch Name</th>
        @foreach ($fields as $field)
        <th>{{ucwords(str_replace("_"," ", $field))}}</th>
        @endforeach
    </thead>
    <tbody>

        @foreach ($branches as $branch)
   
       
        <tr>
            <td>
                <a 
                href="{{route('branchcampaign.show', [$campaign->id, $branch->id])}}">
                {{$branch->id}}
                </a>
            </td>
            <td>{{$branch->branchname}}</td>
             @foreach ($fields as $field)
            <td class="text-right">
                @if(strpos( $field,'value'))
                    ${{number_format($branch->$field,0)}}
                @else
                    {{$branch->$field}}
                @endif
                @php 
                $totals[$field] = isset($totals[$field]) ? $totals[$field] + $branch->$field : $branch->$field  @endphp
            </td>
            @endforeach
        </tr>
       
        @endforeach
    </tbody>
    <tfoot>
        
        <th>Totals:</th>
        <td></td>
        @foreach ($fields as $field)
            <td class="text-right"> 
                @if(strpos($field,'value'))
                   ${{number_format(isset($totals[$field]) ? $totals[$field] : 0,0)}}
                @else
                    {{number_format(isset($totals[$field]) ? $totals[$field] : 0,0)}}
                    @endif
            </td>
        @endforeach
    </tfoot>
</table>