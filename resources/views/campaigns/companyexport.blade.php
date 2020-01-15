@php $fields= [
    "offered_leads",
    "worked_leads",
    "rejected_leads",
    "new_opportunities",
    "won_opportunities",
    "opportunities_open",
    "won_value",
    "open_value" 
    ] 
    @endphp
<table>
    <thead>
        <tr></tr>
        <tr>
            <th>{{$campaign->title}}</th>
        </tr>
        <tr></tr>
        <tr>
            <th>Company Name</th>
            @foreach ($fields as $field)
                <th>{{ucwords(str_replace("_"," ", $field))}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>

        @foreach ($companies as $company)
   
       
        <tr>
            
            <td>{{$company->companyname}}</td>
            @foreach ($fields as $field)
            <td class="text-right">
                @if(strpos( $field,'value'))
                    ${{number_format($company->$field,0)}}
                @else
                    {{number_format($company->$field,0)}}
                @endif
              
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    
</table>