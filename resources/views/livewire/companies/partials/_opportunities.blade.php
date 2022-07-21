<table  class='table table-striped table-bordered table-condensed table-hover'>

    <thead>
        
        <th>
            <a wire:click.prevent="sortBy('companyname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'companyname'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('open_opportunities')" role="button" href="#">
                Open Opportunities
                @include('includes._sort-icon', ['field' => 'companyname'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('open_value')" role="button" href="#">
                Open Value
                @include('includes._sort-icon', ['field' => 'open_value'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('new_opportunities')" role="button" href="#">
                New Opportunities
                @include('includes._sort-icon', ['field' => 'new_opportunities'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('new_value')" role="button" href="#">
                New Value
                @include('includes._sort-icon', ['field' => 'new_value'])
            </a>

        </th>
        <th>
            <a wire:click.prevent="sortBy('won_opportunities')" role="button" href="#">
                Closed-Won 
                @include('includes._sort-icon', ['field' => 'won_opportunities'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('won_value')" role="button" href="#">
                Won Value 
                @include('includes._sort-icon', ['field' => 'won_value'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('lost_opportunities')" role="button" href="#">
                Closed Lost 
                @include('includes._sort-icon', ['field' => 'lost_opportunities'])
            </a>
        </th>
    </thead>
    <tbody>
        @foreach($results as $company)
            <tr>
                <td >{{$company->companyname}}</td>
                <td align="center">{{$company->open_opportunities}}</td>
                <td align="right">${{$company->open_value ? number_format($company->open_value,0):0}}</td>
                <td align="center">{{$company->new_opportunities}}</td>
                <td align="right">${{$company->new_value ? number_format($company->new_value,0):0}}</td>
                <td align="center">{{$company->won_opportunities}}</td>
                <td align="right">${{$company->won_value ? number_format($company->won_value,0):0}}</td>
                <td align="center">{{$company->lost_opportunities}}</td>

            </tr>
        @endforeach

    </tbody>

</table>