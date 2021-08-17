<div>
    <h2>{{$person->fullName()}}'s Dashboard</h2>
    @if($managers)
     <div class="col form-inline"> 
        @include('livewire.partials._NAMselector')
    </div>
    @endif
   
     <div class="col form-inline">  
        @include('livewire.partials._perpage')
        @include('livewire.partials._companyselector')
        @include('livewire.partials._search', ['placeholder'=>'Search addresses'])
    </div>
    <div class="col form-inline"> 
        <label><i class="fas fa-filter text-danger"></i> Filters</label>
        @include('livewire.partials._stateselector')
        @include('livewire.partials._assigned')
        @if ($status != 'Unassigned')
        <label>With / W/O Opportunities</label>
         <select wire:model="withOps" class="form-control">
            @foreach ($opstatus as $key)
                <option value="{{$key}}">{{$key}}</option>
            @endforeach
        </select>
        @endif
    </div>
    <h4>{{$status}} Locations of {{$company->companyname}} in {{$state_code == 'All' ? 'All States' : $state_code}} </h4>
    @if ($status != 'Unassigned')
        @switch($withOps)
        @case('All')
        With or Without Any Opportunities
        @break
        @case('Without')
        {{$withOps}} Any Opportunities
        @break
        @case('Only Open')
        With {{$withOps}} Opportunities
        @break
        @case('Any')
        With {{$withOps}} Opportunities
        @break
        @endswitch
    @endif
    <div wire:loading>
        <div class="spinner-border text-danger"></div>
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
                    <th>Assigned to Branch(es)</th>
                    <th>Opportunities Value</th>                   
                    <th>
                        <a wire:click.prevent="sortBy('last_activity_id')" role="button" href="#">
                            Last activity
                            @include('includes._sort-icon', ['field' => 'last_activity_id'])
                        </a>

                   
                    </th>
                
                
                </tr>
            </thead>
            <tbody>
               @foreach($locations as $lead)

            <tr>
            <td>
                <a href="{{route('address.show',$lead->id)}}">
                    {{$lead->businessname}}
                </a>
            
            </td>

            <td>{{$lead->street}}</td>
            <td>{{$lead->city}}</td>
            <td>{{$lead->state}}</td>
            <td>
                @foreach ($lead->assignedToBranch as $branch)
                    <a href="{{route('branches.show', $branch->id)}}" title="Visit {{$branch->branchname}}">{{$branch->branchname}}
                            </a>
                        
                        {{! $loop->last ? ',' :''}}

                @endforeach
                
            <td>
                {{$lead->opportunities ? '$' . number_format($lead->opportunities->sum('value'),0) : ''}}

            </td>    
            <td>
                @if($lead->lastActivity)
                    ${{$lead->lastActivity->activity_date->format('Y-m-d')}}        
                @endif
            </td>
            
        
    </tr>
    @endforeach
           
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col">
            {{ $locations->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $locations->firstItem() }} to {{ $locations->lastItem() }} out of {{ $locations->total() }} results
        </div>
    </div>
</div>
</div>
