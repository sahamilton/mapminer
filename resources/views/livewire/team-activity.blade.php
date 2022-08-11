<div>
    <h2>{{$manager->completename}}'s Team Mapminer Usage</h2>

    <p><a href="{{route('team.export',$manager->id)}}">Export to Excel</a></p>
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search People'])    
        </div>
    </div>
    
    

    <div class="row mb-4">
        <div class="col form-inline">
            <x-form-select wire:model="role_id"
                    name='role_id'
                    label="Roles:"
                    :options='$roles'
                    />
           
            
        </div>
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    
    </div>

    @include('team.partials._table')
    <div class="row">
        <div class="col">
            {{ $team->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $team->firstItem() }} to {{ $team->lastItem() }} out of {{ $team->total() }} results
        </div>
    </div>

</div>
