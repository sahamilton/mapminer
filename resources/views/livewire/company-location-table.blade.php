<div>
    <h2>{{$company->companyname}} locations 
        @if($state !='All') 
        in {{$state}}
        @endif

    </h2>
    @if($claimed != 'All')
        <p>{{ucwords($claimed)}} by Branches</p>

    @endif
       
    <div class="row" style="margin-top:5px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            
        
            <i class="fas fa-filter text-danger"></i>
            State: &nbsp;
            <select wire:model="state" class="form-control">
                <option value="All">All</option>
                @foreach ($allstates as $state)
                    <option value="{{$state}}">{{$state}}</option>
                @endforeach
            </select>
        
            Claimed: &nbsp;
            <select wire:model="claimed" class="form-control">
                <option value="All">All</option>
                <option value="claimed">Claimed</option>
                <option value="unclaimed">Unclaimed</option>    
                
            </select>
        </div>
        <div wire:loading>
        <div class="spinner-border text-danger"></div>
    </div>
        @include('livewire.partials._search', ['placeholder'=>'Search Locations'])
    </div>
    <div class="row">
        <table 
            class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>
                    <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                        Business Name
                        @include('includes._sort-icon', ['field' => 'businessname'])
                    </a>
                    
                </th>
                <th>Address</th>

                <th>
                    <a wire:click.prevent="sortBy('city')" role="button" href="#">
                        City
                        @include('includes._sort-icon', ['field' => 'city'])
                    </a>
                </th>
                <th><a wire:click.prevent="sortBy('state')" role="button" href="#">
                        State
                        @include('includes._sort-icon', ['field' => 'state'])
                    </a>
                </th>
                <th>Assigned to Branch</th>
                <th>
                    <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                        Distance from you
                    @include('includes._sort-icon', ['field' => 'distance'])
                </th>
               
            </thead>
            <tbody>
                @include('companies.partials._locationstable')
            </tbody>
        </table>
    </div>
    <div class="row">
            <div class="col">
                {{ $locations->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $locations->firstItem() }} to {{ $locations->lastItem() }} out of {{ $locations->total() }} results
            </div>
        </div>
    </div>
</div>