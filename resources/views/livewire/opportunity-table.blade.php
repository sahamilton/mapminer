<div>
  <h2>{{$branch->branchname}} Branch Opportunities</h2>

<p><a href="{{route('branchdashboard.show', $branch->id)}}">Return To Branch {{$branch->id}} Dashboard</a></p>

@php $activityTypes = \App\ActivityType::all(); @endphp
@if(count($myBranches)>1)

<div class="col-sm-4">
   

    <select wire:model="branch_id" class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
          @foreach ($myBranches as $key=>$branchname)
                <option value="{{$key}}">{{$branchname}}</option>
          @endforeach 
    </select>


</div>
@endif  
<div class="row mb-4">
        <div class="col form-inline">
            Per Page: &nbsp;
            <select wire:model="perPage" class="form-control">
                <option>10</option>
                <option>15</option>
                <option>25</option>
            </select>
        </div>
        <div class="col form-inline">
            Status: &nbsp;
            <select wire:model="filter" class="form-control">
                <option value="0">Open</option>
                <option value="1">Closed-Won</option>
                <option value="2">Closed-Lost</option>
            </select>
        </div>

        <div class="col">
            <input wire:model="search" class="form-control" type="text" placeholder="Search opportunities...">
        </div>
    </div>

    <div class="row">
        <table class='table table-striped table-bordered table-condensed table-hover'>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>
                        <a wire:click.prevent="sortBy('created_at')" role="button" href="#">
                        Date Opened
                        @include('includes._sort-icon', ['field' => 'created_at'])
                        </a>
                        
                    </th>
                    <th>Days Open</th>
                    <th>Status</th>
                    <th>Business</th>
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
                    <th>Last Activity</th>
                    @if(auth()->user()->hasRole('branch_manager'))
                    <th>Activities</th>
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

