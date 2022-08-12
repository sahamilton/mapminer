<div>

    <h2>{{ucwords($summaryview)}}</h2>

    <p class="bg-warning">For the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>

    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <x-form-select
                name='summaryview'
                label='View:'
                wire:model="summaryview" 
                :options='$views'
                />
            @if (! $team)
                @include('livewire.partials._search', ['placeholder'=>'Search Branches'])
            @endif
            <div  class="float.right" wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
       
        
        <div class="col form-inline">
            
        </div>
    </div>
    @if(! $team)

        @include('livewire.dashboards.partials._branchsummary')


    @else

        @include('livewire.dashboards.partials._teamsummary')

    @endif

</div>
