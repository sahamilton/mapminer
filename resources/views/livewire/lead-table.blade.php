<div>
    <h1>{{$branch->branchname . " leads"}}</h1>
    <h4>
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
    </h4>
    @if ($this->setPeriod !="All")
    <p>created between the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>
    @else
    <p>created in all time periods</p>
    @endif
    <p><a href="{{route('branchdashboard.show', $branch->id)}}">Return To Branch Dashboard</a></p>

    @include('livewire.partials._branchselector')

    
    <div class="row">
        @include('livewire.partials._perpage')
        <div wire:loading>
            <div class="spinner-border"></div>
        </div>
        @include('livewire.partials._periodselector', ['all'=>true])
        <div class="col form-inline">
            <label>With / W/O Opportunities</label>
             <select wire:model="withOps" class="form-control">
                @foreach ($opstatus as $key)
                    <option value="{{$key}}">{{$key}}</option>
                @endforeach
            </select>
        </div>
        @include('livewire.partials._search', ['placeholder'=>'Search leads'])
        
        
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
                <th>
                    <a wire:click.prevent="sortBy('dateAdded')" role="button" href="#">
                    Lead Added
                     @include('includes._sort-icon', ['field' => 'dateAdded'])
                        </a>
                </th>
                @if(auth()->user()->hasRole(['branch_manager']))
                    <th></th>
                @endif
                </tr>
            </thead>
            <tbody>
               @foreach($leads as $lead)

 <tr>
        <td>
            <a href="{{route('address.show',$lead->id)}}">
                {{$lead->businessname}}
            </a>
            <a data-toggle="modal" 
            data-target="#add-lwactivity" 
            data-title="{{$lead->businessname}}"
            data-address_id = "{{$lead->id}}"
            data-branch_id = "{{$branch->id}}"
            title="Add activity at {{$lead->businessname}}" >
            <i class="fas fa-plus-circle text text-success"></i> </a>
        </td>

        <td>{{$lead->street}}</td>
        <td>{{$lead->city}}</td>
        <td>{{$lead->state}}</td>
        <td>
            @if($lead->leadsource)
             {{$lead->leadsource->source}}
            @endif
        </td>
        
        @if ($branch->currentcampaigns->count())
        <td>
            @foreach ($lead->currentcampaigns as $campaign)
               
                   <li>{{$campaign->title}}</li>
               
            @endforeach
            @if(auth()->user()->hasRole('branch_manager'))
            <a 
                data-pk="{{$lead->id}}"
                data-id="{{$lead->id}}"
                data-toggle="modal" 
                data-target="#addtocampaign" 
                data-title = "" 
                href="#">
                <i class="text-success fas fa-plus-circle"></i> Add to current campaign
            </a>
           @endif
        </td>
        @endif
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
        @if(auth()->user()->hasRole(['branch_manager']))
        <td>
       
            
            <a 
                data-href="{{route('branchleads.destroy',$lead->assignedToBranch->where('id', $branch->id)->first()->pivot->id)}}" 
                data-toggle="modal" 
                data-target="#delete-lead" 
                data-title = "  {{$lead->businessname}} lead from your branch" 
                href="#"><i class="fas fa-trash-alt text-danger"></i>
            </a>  
        </td>
       @endif
    </tr>
@endforeach
           
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
@include('livewire.activities._modal')
