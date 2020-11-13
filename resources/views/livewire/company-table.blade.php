<div>
    <div class="row mb-4">
            @include('livewire.partials._perpage')
             <div class="col form-inline">
                Type:
                <select wire:model="accounttype" 
                class="form-control">
                    <option>All</options>
                    @foreach ($types as $type)
                        <option value="{{$type->id}}">{{$type->type}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col">
                <input wire:model="search" class="form-control" type="text" placeholder="Search companies...">
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
                <th>Service Lines</th>
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

</div>
