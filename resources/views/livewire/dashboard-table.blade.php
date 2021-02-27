<div>

     <div class="row mb-4">
        @include('livewire.partials._perpage')
        <div wire:loading>
            <div class="spinner-border"></div>
        </div>
        <div class="col form-inline">
            Filter: &nbsp;
            <select wire:model="showRoles" class="form-control" multiple>

                @foreach ($roles as $role)
                <option value="{{$role->id}}">{{$role->display_name}}</option>
                @endforeach
            </select>
        </div>
        @include('livewire.partials._search', ['placeholder'=>'Search Managers'])
    </div>

    <div class="row">
        <table
            name="selectManager"
            >
            <thead>
                <th>
                    <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
                        Manager
                        @include('includes._sort-icon', ['field' => 'lastname'])
                    </a>
                </th>
                <th>Role</th>
                <th>Reports To</th>
                <th>Branches</th>
            </thead>
            <tbody>
                @include('dashboards.partials._managerselect')
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col">
            {{ $managers->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $managers->firstItem() }} to {{ $managers->lastItem() }} out of {{ $managers->total() }} results
        </div>
    </div>
</div>
