<div>
    <h2>{{$manager->fullName()}}'s Dashboard</h2>
    <h4>for {{$company->companyname}} locations</h4>
    @include('livewire.partials._periodSelector')
    @include('livewire.partials._companyselector')
    <h4>Account Summary</h4>
     <div wire:loading>
            <div class="spinner-border"></div>
        </div>
    <x-table.table>
        <x-slot name="head">
               <th>Stats</th>
               <th>Summary</th> 
        </x-slot>

        <x-slot name="body">
            <tr>
                <td>Locations</td>
                <td>{{$company->locations_count}}</td>
            </tr>
            <tr>
                <td>Assigned to branches</td>
                <td>{{$company->assigned}}</td>
            </tr>
             <tr>
                <td>Open Opportunities</td>
                <td>{{$company->open_opportunities}}</td>
            </tr>
            <tr>
                <td>Open Opportunities Value</td>
                <td>${{number_format($company->open_value,0)}}</td>
            </tr>
        </x-slot>
    </x-table.table>
    <h4>Branch Summary</h4>
    <p>For the period {{$this->period['from']}} to {{$this->period['to']}}</p>
    @include('livewire.partials._search', ['placehodler'=>'Search branches ...'])
    @include('livewire.partials._perPage')
    <div class="col form-inline">
            <label for="status">Status:</label>
            <select wire:model="status" 
            class="form-control">
                <option value="All">All</option>
                <option value="withOpportunities">with Open Opportunities</option>
            </select>
        </div>
    <x-table.table>
        <x-slot name="head">
           <th>
                <a wire:click.prevent="sortBy('branchname')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                        Branch
                    @include('includes._sort-icon', ['field' => 'branchname'])
                </a>
            </th>
           <th>Manager</th>
            <th>
                <a wire:click.prevent="sortBy('worked_leads')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                    Assigned Leads
                    @include('includes._sort-icon', ['field' => 'worked_leads'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('touched_leads')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                    Touched Leads
                    @include('includes._sort-icon', ['field' => 'touched_leads'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('activities_count')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                    Activities
                    @include('includes._sort-icon', ['field' => 'activities_count'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('opportunities_open')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                    Open Opportunities
                    @include('includes._sort-icon', ['field' => 'opportunities_open'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('opportunities_open')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                    Open Opportunity Value
                    @include('includes._sort-icon', ['field' => 'opportunities_open'])
                </a>
            </th>
        </x-slot>


        <x-slot name="body">
            @foreach ($branches as $branch)
            <tr>
                <td>{{$branch->branchname}}</td>
                <td>
                    @foreach ($branch->manager as $manager)
                        {{$manager->fullName()}}
                    @endforeach
                </td>
                <td>{{$branch->worked_leads}}</td>
                <td>{{$branch->touched_leads}}</td>
                <td>{{$branch->activities_count}}</td>
                <td>{{$branch->opportunities_open}}</td>
                <td>${{number_format($branch->open_value,0)}}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table.table>
    <div class="row">
            <div class="col">
                {{ $branches->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} out of {{ $branches->total() }} results
            </div>
        </div>
    </div>
</div>
