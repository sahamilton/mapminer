
<table>
    <thead>
        <tr></tr>
        <tr><th colspan="9">Branch Pipeline</th></tr>
        <tr><th colspan='9'>For the period from {{now()->format('M Y')}} to {{now()->addMonths(5)->format('M Y')}}</th></tr>
        <tr></tr>
        <tr>
            <th><b>Branch ID</b></th>
            <th><b>Branch Name</b></th>
            <th><b>Branch Manager</b></th>
            @foreach ($period as $month)
            <th><b>{{$month}}</b></th>
            @endforeach
            
        </tr>

    </thead>
    <tbody>
        @foreach ($branches as $branch)
        
        @php 
        $branchresults = array_values(array_intersect_key($results,  array_flip(array_keys(array_column($results, 'id'), $branch->id))));
        
        

        @endphp

            <tr>
                <td>{{$branch->id}}</td>

                <td>{{$branch->branchname}}</td>
                
                <td>
                    @foreach ($branch->manager as $manager)
                    {{$manager->fullName()}}
                    @if(! $loop->last)/@endif
                    @endforeach
                </td>
                @foreach ($period as $month)
                    <td>
                        @if($branchresults)
                            
                            @php $monthkey = array_keys(array_column($branchresults,'month'), $month); 
                            $thismonth = reset($monthkey);

                            @endphp
                           
                            @if(isset($branchresults[$thismonth]))
                                 
                                {{$branchresults[$thismonth]->value}}
                            @else
                                
                            0

                            @endif
                            
                            
                        @else
                        0
                        @endif
                    </td>
                   @endforeach
                
            </tr>
        @endforeach
    </tbody>
</table>