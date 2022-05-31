<div>
    <h2>{{ucwords($status)}} Trainings
    @if($selectRole != 'All')
       For {{$roles[$selectRole]}}'s
    @endif </h2>
    
    <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <i class="fas fa-search"></i> <input wire:model="search" class="form-control" type="text" placeholder="Search training...">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            <label><i class="fas fa-filter text-danger"></i>Filters:&nbsp;  </label>
            <x-form-select wire:model="selectRole" name="selectRole"  :options="$roles" label="Roles: "  />
            <x-form-select wire:model="status" name="status"  :options="$statuses" label="Status: "  />

            <div>
                <div wire:loading>
                    <div class="spinner-border text-danger"></div>
                    
                </div>
                
               
            </div>
        </div>

    </div>

    @include('training.partials._table')
    <div class="row">
        <div class="col">
            {{ $trainings->links()}}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $trainings->firstItem() }} to {{ $trainings->lastItem() }} out of {{ $trainings->total() }} results
        </div>
    </div>

</div>
