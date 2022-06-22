<div>
    <h2> Roles </h2>
   <div class="page-header">
        <h3>Role Management</h3>

            <div class="float-right">
                <a href="{{{ route('roles.create') }}}" class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>
 Create New Role</a>
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
                name="permission_id"
                wire:model="permission_id"
                label="with Permission:"
                :options="$permissions" />
           
            
        </div>
    </div>
    <div class="row">
        @include('admin.roles.partials._table')
    </div>
</div>
