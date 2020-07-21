<div>
<h1>{{$branch->branchname . " leads"}}</h1>
<p><a href="{{route('branchdashboard.show', $branch->id)}}">Return To Branch Dashboard</a></p>

@if(count($myBranches) > 1)
    <div class="col-sm-4">
       
            <select
                wire:model="branch_id" 
                class="form-control input-sm" 
                id="branchselect" 
                name="branch" 
                onchange="this.form.submit()">
                @foreach ($myBranches as $key=>$branchname)
                    <option {{$branch->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branchname}}</option>
                @endforeach 
            </select>

        
    </div>
@endif


    <div class="row mb-4">
        @include('livewire.partials._perpage')

        <div class="col">
            <input wire:model="search" class="form-control" type="text" placeholder="Search leads...">
        </div>
    </div>

    <div class="row">
        <table class='table table-striped table-bordered table-condensed table-hover'>
            <thead>
                <tr>
                    <th>
                        <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                            Business
                            @include('includes._sort-icon', ['field' => 'businessname'])
                        </a>

                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('street')" role="button" href="#">
                            Street
                            @include('includes._sort-icon', ['field' => 'street'])
                        </a>
                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('city')" role="button" href="#">
                            City
                            @include('includes._sort-icon', ['field' => 'city'])
                        </a>
                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('state')" role="button" href="#">
                            State
                            @include('includes._sort-icon', ['field' => 'state'])
                        </a>
                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('state')" role="button" href="#">
                            Source
                            @include('includes._sort-icon', ['field' => 'lead_source_id'])
                        </a>
                       
                    </th>
                    @if($branch->currentcampaigns->count())
                        <th>Campaign</th>
                    @endif
                    <th>
                    <a wire:click.prevent="sortBy('last_activity_id')" role="button" href="#">
                            Last activity
                            @include('includes._sort-icon', ['field' => 'last_activity_id'])
                        </a>

                   
                </th>
                    <th></th>
                    
                </tr>
            </thead>
            <tbody>

                @include('leads.partials._table')
                
           
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col">
            {{ $leads->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} out of {{ $leads->total() }} results
        </div>
    </div>
</div>
