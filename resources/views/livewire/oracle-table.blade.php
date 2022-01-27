<div>
    
    <h3>Users in Mapminer, not in Oracle HR</h3>

    
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <i class="fas fa-search"></i> <input wire:model="search" class="form-control" type="text" placeholder="Search users...">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            <label><i class="fas fa-filter text-danger"></i>Filters:&nbsp;  </label>
            
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
    @include('oracle.partials._matchtable')
    <div class="row">
        <div class="col">
            {{ $users->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
        </div>
    </div>

    
</div>
