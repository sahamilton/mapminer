<div>
    <div class="row mb-4">
        @include('livewire.partials._perpage')
        <div class="col">
            <input wire:model="search" class="form-control" type="text" placeholder="Search users...">
        </div>
    </div>
    <table class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <tr>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('id')" role="button" href="#">
                        Employee ID
                        @include('includes._sort-icon', ['field' => 'employee_id'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('firstname')" role="button" href="#">
                    First Name
                    @include('includes._sort-icon', ['field' => 'firstname'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
                    Last Name
                    @include('includes._sort-icon', ['field' => 'lastname'])
                </a>
            </th>

            <th class="col-md-2">
                <a wire:click.prevent="sortBy('email')" role="button" href="#">
                    Email
                    @include('includes._sort-icon', ['field' => 'email'])
                </a>
            </th>
            <th class="col-md-2">Roles</th>
            <th class="col-md-2">Service Lines</th>
            <th class="col-md-2">LastLogin</th>
            <th class="col-md-2">LastUpdate</th>
            <th class="col-md-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.users.partials._usertable')
        </tbody>

    </table>
    <div class="row">
        <div class="col">
            {{ $users->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
        </div>
    </div>
</div>