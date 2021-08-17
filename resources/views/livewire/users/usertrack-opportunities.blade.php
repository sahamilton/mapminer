<div>
 <p>for the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
@php $activityTypes = \App\ActivityType::all(); @endphp
 
<div class="row mb-4">
        
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._periodSelector')
            Status: &nbsp;
            <select wire:model="filter" class="form-control">
                <option value="all">All</option>
                <option value="0">Open</option>
                <option value="1">Closed-Won</option>
                <option value="2">Closed-Lost</option>
            </select>
        </div>

        <div class="row">
            <input wire:model="search" class="form-control" type="text" placeholder="Search opportunities...">
        </div>
    </div>

    <div class="row">
        <table class='table table-striped table-bordered table-condensed table-hover'>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>
                        <a wire:click.prevent="sortBy('opportunities.created_at')" role="button" href="#">
                        Date Opened
                        @include('includes._sort-icon', ['field' => 'opportunities.created_at'])
                        </a>
                        
                    </th>
                    <th>Days Open</th>
                    <th>Status</th>
                    <th>
                        <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                            Business
                            @include('includes._sort-icon', ['field' => 'businessname'])
                        </a>
                    </th>
                    <th>Address</th>
                    <th>Top 25</th>
                    <th>Potential Headcount</th>
                    <th>Potential Duration (mos)</th>
                    <th>
                        <a wire:click.prevent="sortBy('value')" role="button" href="#">
                            Potential $$
                            @include('includes._sort-icon', ['field' => 'value'])
                        </a>
                    </th>
                    <th>
                    <a wire:click.prevent="sortBy('expected_close')" role="button" href="#">
                            Expected Close
                            @include('includes._sort-icon', ['field' => 'expected_close'])
                        </a>
                    </th>
                    
                    <th>
                    <a wire:click.prevent="sortBy('actual_close')" role="button" href="#">
                    Actual Close
                    @include('includes._sort-icon', ['field' => 'actual_close'])
                </th>
                    <th>
                    <a wire:click.prevent="sortBy('last_activity_id')" role="button" href="#">
                    Last Activity
                    @include('includes._sort-icon', ['field' => 'last_activity_id'])
                </th>
                    
                </tr>
            </thead>
            <tbody>

                @include('opportunities.partials._actionstable')


            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col">
            {{ $opportunities->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $opportunities->firstItem() }} to {{ $opportunities->lastItem() }} out of {{ $opportunities->total() }} results
        </div>
    </div>
</div>


