<table  class='table table-striped table-bordered table-condensed table-hover'>

    <thead>
        
        <th>
            <a wire:click.prevent="sortBy('companyname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'companyname'])
            </a>
        </th>
        @foreach ($activitytypes as $activity)
        <th align='center'>
            
                {{$activity}}
               
            </a>
        </th>
        @endforeach
    </thead>
    <tbody>
        @foreach($results as $company)
            <tr>
                <td >{{$company->companyname}}</td>
                

                @foreach ($activitytypes as $activity)
                    <td align="center">
                        
                        {{$company->activities->where('activity', $activity)->first() ? $company->activities->where('activity', $activity)->first()->activities :0}}
                    </td>
                @endforeach

            </tr>
        @endforeach

    </tbody>

</table>