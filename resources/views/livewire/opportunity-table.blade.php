<div>
    <h2>{{$branch->branchname}} Branch {{$filters[$filter]}} Opportunities</h2>

    @if (! in_array($this->setPeriod,["All", 'allDates']))
    <p class="bg-warning">Created between the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>
    @else
    <p>Created in all time periods</p>
    @endif
    <p>
        <a href="{{route('branchdashboard.show', $branch->id)}}">
            Return To Branch {{$branch->id}} Dashboard
        </a>
    </p>

    <div class="row" style="margin-bottom:10px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._branchselector')
            @include('livewire.partials._search', ['placeholder'=>'Search opportunities'])
        </div>
    </div>
    
    
    <div class="row mb-4">
        <div class="col form-inline">
            <label><i class="fas fa-filter text-danger"></i>&nbsp;&nbsp;Filter&nbsp;&nbsp;</label>
            @include('livewire.partials._periodselector', ['all'=>true])
            <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>
        
            
            Status: &nbsp;
            <select wire:model="filter" class="form-control">
                @foreach ($filters as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
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
                    @if(auth()->user()->hasRole('branch_manager'))
                    <th></th>

                    @endif
                </tr>
            </thead>
            <tbody>

                @include('opportunities.partials._table')


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
@include('opportunities.partials._activitiesmodal')

