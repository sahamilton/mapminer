<table>
    <thead>
        <tr></tr>
        <tr>
            <th>{{$campaign->title}}</th>
        </tr>
        <tr></tr>
        <tr>
            <th>Branch</th>
            <th>Branch Name</th>
            @foreach ($fields as $field)
                <th>{{ucwords(str_replace("_"," ", $field))}}</th>
            @endforeach
            
        </tr>
    </thead>
    <tbody>

        @foreach ($branches as $branch)
   
       
        <tr>
            <td>{{$branch->id}}</td>
            <td>{{$branch->branchname}}</td>
            @foreach ($fields as $field)
            <td>
                @if(strpos( $field,'value'))
                    ${{number_format($branch->$field,0)}}
                @else
                    {{number_format($branch->$field,0)}}
                @endif
              
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    
</table>