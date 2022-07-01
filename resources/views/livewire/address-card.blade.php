<div>


        <h2>{{$address->businessname}}</h2>
        <h4>{{$address->fulladdress()}}</h4>
        @if(auth()->user()->hasRole(['branch_manager', 'staffing_specialist', 'market_manager']))
       <p><a href="{{route('branch.leads')}}" >Return to branch leads</a></p>

       @endif
        
        <div class="row mb-4">
            <form class="form-inline">
                <x-form-select 
                    class="form-control col-md-20"
                    name='view' 
                    wire:model="view"
                    label="View:"
                    :options="$viewtypes" 
                />
            </form>
        
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>

        @switch ($view)
            @case('summary')
                @include('addresses.partials._lwdetails')
            @break;
            @case('contacts')
                <livewire:address-contacts :address_id="$address->id" :owned="$owned" />
            @break;
            @case('activities')
                <livewire:address-activities :address="$address" :owned="$owned" />
            @break;
            @case('opportunities')
                <livewire:address-opportunities :address="$address" :owned="$owned" />
            @break;
        @endswitch



</div>
