<div>


        <h2>{{$address->businessname}}</h2>

        <h4>{{$address->fulladdress()}}</h4>
        @if(auth()->user()->hasRole(['branch_manager', 'staffing_specialist', 'market_manager']))
       <p><a href="{{route('branch.leads')}}" >Return to branch leads</a></p>

       @endif
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">

            @foreach ($viewtypes as $key=>$v)
                <a wire:click="changeView('{{$key}}')" 
                class="nav-link nav-item @if($key === $view) active @endif " >
                    <strong>{{$v}} {{isset($address->$key) ? "(" . $address->$key->count() . ")" : ''}}</strong>
                </a>
            @endforeach

              
            </div>
        </nav>
        <div class="row mb-4">
            
        
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>

        @switch ($view)
            @case('summary')
                @include('addresses.partials._lwdetails')
            @break;
            @case('contacts')
                <livewire:address-contacts :address_id="$address_id" :owned="$owned" />
            @break;
            @case('activities')
                <livewire:address-activities :address="$address" :owned="$owned" />
            @break;
            @case('opportunities')
                <livewire:address-opportunities :address="$address" :owned="$owned" />
            @break;
        @endswitch



</div>
