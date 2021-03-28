<div>
    <h2>Leads by Lead Source</h2>
    <p>
        @if ($branch_id =='All')
           For All Branches
        @else
            For Branch {{$branch->branchname}}
        @endif
    </p>
      

    
    
    <div wire:loading>
        <div class="spinner-border"></div>
    </div>
    @include('livewire.partials._branchselector')
    
    <div class="row mb-4 ">
        @include('livewire.partials._perpage')
        @include('livewire.partials._staleMonths')
        
       
    </div>
    <div class="row mb-4 ">
        @include('livewire.partials._search', ['placeholder'=>'Search Leadsource'])
    </div>
    @include('leadsource.partials._table')
</div>
