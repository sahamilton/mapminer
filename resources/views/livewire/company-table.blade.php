<div>
    <h2>Companies</h2>
    <h4>with locations within {{$distance}} miles</h4>
    <x-form-input name="address" wire:model="address" class="form-control" /> {{$address}}
    <div class="row mb-4">
        
         <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Companies'])
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            <i class="fas fa-filter text-danger"></i> Type:
            <x-form-select wire:model='accounttype' class="form-control" name="accounttype" label='Account Type:' :options='$types' />
            <x-form-select wire:model='distance' class="form-control" name="distance" label='Distance' :options='$distances' />
        </div>

        
    </div>
    <table class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <tr>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('companyname')" role="button" href="#">
                        Company
                        @include('includes._sort-icon', ['field' => 'companyname'])
                </a>
            </th>
            <th>Customer Id</th>
            <th>Company Type</th>
            <th>Manager</th>
            <th>Email</th>
            <th>Vertical</th>
            <th>Locations</th>
           
            @if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations'))

            <th>Actions</th>
            @endif
            </tr>
        </thead>
        <tbody>
            @include('companies.partials._companytable')
        </tbody>

    </table>
    <div class="row">
        <div class="col">
            {{ $companies->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $companies->firstItem() }} to {{ $companies->lastItem() }} out of {{ $companies->total() }} results
        </div>
    </div>

</div>
