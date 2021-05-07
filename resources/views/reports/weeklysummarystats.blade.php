<table>
    <thead>
        <tr></tr>
        <tr><th>Mapminer Summary Statistics</th></tr>
        <tr>
            @ray($manager)
            @if($manager->count()) 
              <th> for {{$manager->fullName()}}'s team</th>
            @endif
        </tr>
        <tr><th>For the period from  {{$results['period']['current']['from']->format('M jS,Y')}} to {{$results['period']['current']['to']->format('M jS,Y')}}</th></tr>
        <tr><th>compared to  {{$results['period']['prior']['from']->format('M jS,Y')}} to {{$results['period']['prior']['to']->format('M jS,Y')}}</th></tr>
        <tr></tr>
        <tr>
            <th><b>Statistic</b></th>
            <th><b>Current Period</b></th>
            <th><b>Prior Period</b></th>
            
            
        </tr>

    </thead>
    <tbody>
        @foreach ($results['current'] as $element=>$value)
        <tr>
            <td>{{ucwords(str_replace("_"," ", $element))}}</td> 
            <td>{{number_format($value, 0)}}</td> 
            <td>{{number_format($results['prior'][$element],0)}} </td>
            
        </tr>
        @endforeach
    </tbody>
</table>