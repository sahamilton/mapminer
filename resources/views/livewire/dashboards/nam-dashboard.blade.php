<div>
    <h2>{{$person->fullName()}}'s Dashboard</h2>

    @include('livewire.partials._perpage')
    @include('livewire.partials._companyselector')
    @include('livewire.partials._search', ['placeholder'=>'Search addresses'])
    @include('livewire.partials._stateselector')
    <h4>Locations of {{$company->companyname}} in {{$state_code == 'All' ? 'All States' : $state_code}} </h4>

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
                
                {{$lead->assignedToBranch ? implode(",",$lead->assignedToBranch->pluck('branchname')->toArray()) : ''}}
                
            <td>
                @if($lead->lastActivity)
                    {{$lead->lastActivity->activity_date->format('Y-m-d')}}        
                @endif
            </td>
            <td>
            @if($lead->dateAdded)
                {{$lead->dateAdded->format('Y-m-d')}}
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
