<div>
    <div class="row" style="margin-top:5px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            
            @include('livewire.partials._search', ['placeholder'=>'Search Branches'])
            <div wire:loading>
                <div class="spinner-border"></div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:5px">

        <div class="col form-inline">
            <i class="fas fa-filter text-danger"></i>
            State: &nbsp;
            <select wire:model="state" class="form-control">
                <option value="All">All</option>
                @foreach ($allstates as $state)
                    <option value="{{$state->state}}">{{$state->state}}</option>
                @endforeach
            </select>
        </div>
        <div class="col form-inline">
            Service Line: &nbsp;
            <select wire:model="serviceline" class="form-control">
                <option value="All">All</option>
                @foreach ($userServiceLines as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="col form-inline">
            Region: &nbsp;
            <select wire:model="region" class="form-control">
                <option value="All">All</option>
                @foreach ($regions as $region)
                    <option value="{{$region->id}}">{{$region->region}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <table 
            class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

            <th>
                <a wire:click.prevent="sortBy('branchname')" role="button" href="#">
                    Branch
                    @include('includes._sort-icon', ['field' => 'branchname'])
                </a>
                
            </th>

           
            <th>Service Line</th>
            <th>Branch Address</th>
            <th>City</th>
            <th>State</th>
            <th>Region</th>
            <th>Manager</th>

            @can('manage_branches')
            <th>Actions</th>
            @endcan
            </th>

               
            </thead>
            <tbody>
                @include('branches.partials._branchtable')
            </tbody>
        </table>
    </div>
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