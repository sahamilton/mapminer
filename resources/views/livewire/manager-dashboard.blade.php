<div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <h2>{{$manager->completename}}'s Dashboard</h2>
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

            @case('dashboard')
                @include('dashboards.partials._mgrdashboard')
            @break;

            @case('summary')
                <div><livewire:branch-dashboard-summary :manager='$manager->id'  /></div>
            @break;

            

            @case('charts')
                {{isset($branch) ? $branch_id = $branch->id : $branch = null}}
               
                <livewire:branch-activity-chart :period='$period'  :branch_id='$branch_id' />
            @break;

            

        @endswitch
    </div>
    <div class="m-4 clear" /></div>

</div>