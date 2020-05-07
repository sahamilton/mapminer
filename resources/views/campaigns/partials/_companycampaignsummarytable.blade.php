<table id="sorttable"
    name="companysummary"
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
            
                @if(strpos( $field,'value'))
                    <td class="text-right">
                        ${{number_format($branch->$field,0)}}
                    </td>
                @else
                    <td class="text-center">
                        {{$branch->$field}}
                    </td>
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
            
                @if(strpos($field,'value'))
                    <td class="text-right"> 
                       ${{number_format(isset($totals[$field]) ? $totals[$field] : 0,0)}}
                    </td>
                @else
                     <td class="text-center">
                        {{number_format(isset($totals[$field]) ? $totals[$field] : 0,0)}}
                    </td>
                @endif
            </td>
        @endforeach
    </tfoot>
</table>