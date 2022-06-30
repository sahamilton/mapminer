<div>
    @wire
    @ray($address);
        <h2>{{$address->businessname}}</h2>
        <h4>{{$address->fulladdress()}}</h4>
   
        <div class="row mb-4">
            <form class="form-inline">
                <x-form-select 
                    class="form-control col-md-20"
                    name='view' 
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
                @include('addresses.partials._lwcontacts')
            @break;
            @case('activities')
                @include('addresses.partials._lwactivities')
            @break;
            @case('opportunities')
                @include('addresses.partials._lwopportunities')
            @break;
        @endswitch
    @endwire


</div>
