<div>
    <h2>
        @if ($branch_id !== 'all')
        {{$branch->branchname}}
        @else
        All Branches
        @endif
    </h2>
   
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._branchselector')
            @include('livewire.partials._periodselector')

            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>
    
    <livewire:branch-dashboard-summary :branch_id='$branch->id' :period='$period'  />
    <livewire:calendar :branch_id='$branch->id' :period='$period'  />
    
    
</div>
