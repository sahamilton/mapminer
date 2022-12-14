<div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <h2>
        @if ($branch_id !== 'all')
            {{$branch->branchname}}
        @else
            All Branches
        @endif
    </h2>

    @if(auth()->user()->hasRole(['market_manager']))
    <p>

        <a href="{{route('mgrdashboard.index')}}" >Return to Manager Dashboard</a>
    </p>
    @endif
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @if ($view != 'activities')
            @include('livewire.partials._perpage')
            @endif
            @include('livewire.partials._branchselector')
            @if ($view != 'activities')
                @include('livewire.partials._periodselector')
            @endif

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
            @php 

            $agent = new \Jenssegers\Agent\Agent();
            @endphp
            @if($agent->isMobile() || $agent->isTablet())
                <a href="{{route('mobile.index')}}" 
                    class="nav-link nav-item">

                    <strong>Mobile View</strong>
                </a>
            @endif

        </div>
    </nav>
    <div>
        @switch ($view)
            @case('summary')

                <livewire:branch-dashboard-summary :branch_id='$branch->id' :period='$period'  />
            
            @break;

            @case('activities')

                <livewire:calendar :branch_id='$branch->id' :period='$period'  />
            @break;

            @case('charts')
                <livewire:branch-activity-chart :branch_id='$branch->id' :period='$period'  />
            @break;

            @case('team')
                <livewire:branch-details :branch_id='$branch->id' :noheading='true' />
            @break;

        @endswitch
        
    </div>
    <div class="m-4 clear" /></div>

</div>
</div>
