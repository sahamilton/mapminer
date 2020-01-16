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
            
                @if(strpos( $field,'value'))
                <td class="text-right">
                    ${{number_format($company->$field,0)}}
                </td>
                @else
                 <td class="text-center">
                    {{$company->$field}}
                </td>
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
          
                @if(strpos($field,'value'))
                    <td class="text-right"> 
                       ${{number_format(isset($totals[$field]) ? $totals[$field] : 0,0)}}
                   </td>
                @else
                     <td class="text-center"> 
                        {{number_format(isset($totals[$field]) ? $totals[$field] : 0,0)}}
                    </td>
                @endif
        
        @endforeach
        
      
    </tfoot>
</table>