<div>
    <div class="row" style="margin-top:5px">
        @include('livewire.partials._perpage')
        

        <div class="col">
            <input wire:model="search" class="form-control" type="text" placeholder="Search locations...">
        </div>
    </div>
    <div class="row" style="margin-top:5px">
        <div class="col form-inline">
            State: &nbsp;
            <select wire:model="state" class="form-control">
                <option value="All">All</option>
                @foreach ($allstates as $state)
                    <option value="{{$state->state}}">{{$state->state}}</option>
                @endforeach
            </select>
        </div>
        
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

                <th><a wire:click.prevent="sortBy('city')" role="button" href="#">
                        City
                        @include('includes._sort-icon', ['field' => 'city'])
                    </a>
                </th>
                <th><a wire:click.prevent="sortBy('state')" role="button" href="#">
                        State
                        @include('includes._sort-icon', ['field' => 'state'])
                    </a>
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