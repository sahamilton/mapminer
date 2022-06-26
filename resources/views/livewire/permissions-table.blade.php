<div>
    <h2> Permissions </h2>
        <div class="float-right">
            <a href="{{{ route('permissions.create') }}}" class="btn btn-small btn-info iframe">

            <i class="fas fa-plus-circle " aria-hidden="true"></i>

            Create New Permission</a>
        </div>
    <div class="row mt-4" >
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            
            @include('livewire.partials._search', ['placeholder'=>'Search Roles'])
            <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>
        </div>
    </div>
    <div class = "row my-2" >

        <div class="col form-inline">
            

            <x-form-select 
                name="role_id"
                wire:model="role_id"
                label="with Roles:"
                :options="$roles" />
           
            
        </div>
    </div>
    <div class="row">
        @include('admin.permissions.partials._table')
    </div>
</div>
