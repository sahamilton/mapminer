<div>
    <h3>{{ucwords($status)}} User / People Management</h3>

    <div class="float-right">
        <a href="{{{ route('users.create') }}}" class="btn btn-small btn-info iframe">        
            <i class="fas fa-plus-circle " aria-hidden="true"></i>
         Create
        </a>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <i class="fas fa-search"></i> <input wire:model="search" class="form-control" type="text" placeholder="Search users...">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            <label><i class="fas fa-filter text-danger"></i>Filters:&nbsp;  </label>
            <label>&nbsp;Status:&nbsp;</label>
            <select name="status"
                wire:model="status" 
                class="form-control">
                @foreach ($statuses as $state)
                    <option value="{{$state}}">
                        {{ucwords($state)}}
                    </option>
                @endforeach
            </select>
            <label>&nbsp;Role:&nbsp;</label>
            <select name="selectRole"
                wire:model="selectRole"
                class="form-control">
                <option value='All'>All</option>
                @foreach ($roles as $role)
                    <option value="{{$role->id}}">
                        {{$role->display_name}}
                    </option>
                @endforeach
            </select>
            <label>&nbsp;Service Lines:&nbsp;</label>
            <select name="serviceline"
                wire:model="serviceline"
                class="form-control">
                <option value='All'>All</option>
                @foreach ($servicelines as $key=>$value)
                    <option value="{{$key}}">
                        {{$value}}
                    </option>
                @endforeach
            </select>
     
            
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
                <a wire:click.prevent="sortBy('users.email')" role="button" href="#">
                    Email
                    @include('includes._sort-icon', ['field' => 'users.email'])
                </a>
            </th>
            <th class="col-md-2">Roles</th>
            <th class="col-md-2">Service Lines</th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('lastlogin')" role="button" href="#">
                    LastLogin
                    @include('includes._sort-icon', ['field' => 'users.lastlogin'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('updated_at')" role="button" href="#">
                    LastUpdate
                    @include('includes._sort-icon', ['field' => 'users.updated_at'])
                </a>
            </th>
            @if($status !='current')
                <th class="col-md-2">
                    <a wire:click.prevent="sortBy('users.deleted_at')" role="button" href="#">
                        Deleted
                        @include('includes._sort-icon', ['field' => 'users.deleted_at'])
                    </a>
                </th>
            @endif
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
