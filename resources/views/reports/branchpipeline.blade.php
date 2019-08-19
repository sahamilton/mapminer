
<table>
    <thead>
        <tr></tr>
        <tr><th colspan="9">Branch Pipeline</th></tr>
        <tr><th colspan='9'>For the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</th></tr>
        <tr></tr>
        <tr>
            <th><b>Branch ID</b></th>
            <th><b>Branch Name</b></th>
            <th><b>Branch Manager</b></th>
            @foreach ($periods as $per)
            <th><b>{{$per}}</b></th>
            @endforeach
            
        </tr>

    </thead>
    <tbody>
        @foreach ($branches as $branch)
        
            <tr>
                <td>{{$branch->id}}</td>

                <td>{{$branch->branchname}}</td>
                
                <td>
                    @foreach ($branch->manager as $manager)
                    {{$manager->fullName()}}
                    @if(! $loop->last)/@endif
                    @endforeach
                </td> 
                @foreach($periods as $per)
                    @php $opp = $branch->opportunities->where('yearweek', $per); @endphp
                    <td>
                        @if($opp->count() ==1)
                            ${{number_format($opp->first()->funnel,0)}}
                        @else
                        0
                        @endif
                    </td>
                    
                @endforeach
                
            </tr>
        @endforeach
    </tbody>
</table>