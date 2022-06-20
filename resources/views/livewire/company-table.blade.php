<div>
    <h2>@if(isset($accounttype)) {{$types[$accounttype]}} @endif Companies</h2>
    @if($distance !='any')
    <h4>with locations within {{$distance}} miles</h4>
    of {{$address}}
    @endif
   
        
     <div class="row m-4 form-inline">
        @include('livewire.partials._perpage')
        @include('livewire.partials._search', ['placeholder'=>'Search Companies'])
    </div>
    
    <div class="row mb-4">
        <div class="col form-inline">
            <i class="fas fa-filter text-danger mr-4"></i> 
            <x-form-select  class="mx-4" wire:model='accounttype'  name="accounttype" label='Account Type:' :options='$types' />
            <x-form-select class="mx-4"  wire:model='distance'  name="distance" label='Distance:' :options='$distances' />
            <x-form-select class="mx-4"  wire:model='vertical'  name="vertical" label='Industry:' :options='$verticals' />
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
            <th>Locations within {{$distance}} miles</th>
           
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
