<div>
    <h2> 
       {{$state}} Branches
    </h2>
    
    @if($distance !='all')    
   <h4> within {{$distance}} miles of {{$address}}</h4>
    @endif
    <p><a href="{{route('branches.showstatemap', $state)}}">Show Map View</a></p>
    <div class="row mt-4" >
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            
            @include('livewire.partials._search', ['placeholder'=>'Search Branches'])
            <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>
        </div>
    </div>
    <div class = "row my-2" >

        <div class="col form-inline">
            @if($distance === 'all')
            <i class="fas fa-filter text-danger"></i>
            State: &nbsp;
            <select wire:model="state" class="form-control">
                <option value="All">All</option>
                @foreach ($allstates as $state)
                    <option value="{{$state->state}}">{{$state->state}}</option>
                @endforeach
            </select>
            @endif

            <x-form-select name="distance"
                wire:model="distance"
                label="Distance:"
                :options="$distances" />
            &nbsp;of address &nbsp;
             
            <form wire:submit.prevent="updateAddress">
                <input class="form-control" 
                    wire:model.defer="address"
                    required
                    type="text" 
                    value="{{$address ? $address : 'Enter an address'}}"
                    />
                    <button title="Search from an address" type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i>
                    </button>
            </form>
            @if(auth()->user()->hasRole(['admin', 'sales_ops']))
           
                Managers: &nbsp;
                <select wire:model="manager" class="form-control">
                    <option value="All">All</option>
                    <option value="with">With manager</option>
                    <option value="without">Without manager</option>
                    
                </select>
            @endif
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
            
            <th>Region</th>
            <th>Manager</th>
            <th>
                <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                    Distance
                 @include('includes._sort-icon', ['field' => 'distance'])
                </a>
            </th>
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