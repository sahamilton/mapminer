<table  class='table table-striped table-bordered table-condensed table-hover'>

    <thead>
        
        <th>
            <a wire:click.prevent="sortBy('companyname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'companyname'])
            </a>
        </th>
        <th>Account Type</th>
        <th>Industry</th>
        <th>
            <a wire:click.prevent="sortBy('locations_count')" role="button" href="#">
                    Locations
                @include('includes._sort-icon', ['field' => 'locations_count'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('assigned')" role="button" href="#">
                Assigned to Branches
                @include('includes._sort-icon', ['field' => 'assigned'])
            </a>
        </th>
        <th>
             <a wire:click.prevent="sortBy('activities_count')" role="button" href="#">
                Period Activities
                @include('includes._sort-icon', ['field' => 'activities_count'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('open_opportunities')" role="button" href="#">
            Period Opportunities
            @include('includes._sort-icon', ['field' => 'open_opportunities'])
            </a>
        </th>
    </thead>
    <tbody>
        @foreach($results as $company)
            <tr>
                <td>{{$company->companyname}}</td>
                <td>{{$company->type ? $company->type->type : ''}}</td>
                <td>{{$company->industryVertical? $company->industryVertical->filter : ''}}</td>
                <td align='center'>{{$company->locations_count}}</td>
                <td align='center'>{{$company->assigned}}</td>
                <td align='center'>{{$company->activities_count}}</td>
                <td align='center'>{{$company->open_opportunities}}</td>

            </tr>


        @endforeach

    </tbody>

</table>