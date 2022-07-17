<div>
    
   <h2>
        @if($company_id =='All')
            All
        @endif
        @if($type_id != 'All')
           {{ $types[$type_id]}} 
        @endif
        Accounts
        @if($manager_id !='All')
            Managed By {{$managers[$manager_id]}}
        @endif
    </h2>
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Companies'])  
            @include('livewire.partials._periodselector')
            <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>  
            
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            @if(auth()->user()->person->id != $manager_id)
            <x-form-select wire:model="manager_id"
                name="manager_id"
                label=" Manager: "
                :options="$managers"
                />
            @endif
            
            <x-form-select wire:model="type_id"
                name="type_id"
                label=" Account Type: "
                :options="$types"
                />
           <x-form-select wire:model="view"
                name="view"
                label=" View: "
                :options="$views"
                />
        </div>

    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            <x-form-select wire:model="company_id"
                name="company_id"
                label=" Companies: "
                :options="$companies"
                />
        </div>
    </div>

   @switch($view)

    @case('summary')
        @include('livewire.companies.partials._summary')
    @break
     @case('activities')
        @include('livewire.companies.partials._activities')
    @break
    @case('opportunities')
        @include('livewire.companies.partials._opportunities')
    @break


   @endswitch
    <div class="row">
        <div class="col">
            {{ $results->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} out of {{ $results->total() }} results
        </div>
    </div>
</div>
