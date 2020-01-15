<table id="sorttable"
    name="companysummary"
    class="table table-striped"
    >
    <thead>
        <th>Company</th>
        @foreach ($fields as $field)
        <th>{{ucwords(str_replace("_"," ", $field))}}</th>
        @endforeach
    </thead>
    <tbody>

        @foreach ($companies as $company)
    

       
        <tr>
            <td>
                <a href="{{route('campaigns.company.detail', [$campaign->id, $company->id])}}">
                    {{$company->companyname}}
                </a>
            </td>
           @foreach ($fields as $field)
            <td class="text-right">
                @if(strpos( $field,'value'))
                    ${{number_format($company->$field,0)}}
                @else
                    {{$company->$field}}
                @endif
                @php 
                $totals[$field] = isset($totals[$field]) ? $totals[$field] + $company->$field : $company->$field  @endphp
            </td>
            @endforeach
            
        </tr>
       
        @endforeach
    </tbody>
    <tfoot>
        
        <th>Totals:</th>
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