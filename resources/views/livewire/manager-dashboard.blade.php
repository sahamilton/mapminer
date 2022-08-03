<div>

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
    <div>
        @switch ($view)
            @case('summary')
                <livewire:mgr-summary :manager='$manager->id'  />
            @break;

            

            @case('charts')
                <livewire:branch-activity-chart :branch_id='$branch->id' :period='$period'  />
            @break;

            

        @endswitch
    </div>
    <div class="m-4 clear" /></div>

</div>