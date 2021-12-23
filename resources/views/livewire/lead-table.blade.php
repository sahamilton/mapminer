<div>
    
    <h1>{{$branch->branchname . ($type=='Customers' ? ' Customers' : ' Leads')}}</h1>

    <h4>
        @if ($campaign_id != 'All')
            Included in the  {{$campaigns[$campaign_id]}} Campaign
        @elseif($lead_source_id != 'All') 
            From the  {{$leadsources[$lead_source_id]}} Source

        @endif
   
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
 
    @if (! in_array($this->setPeriod, ["All" ,'allDates']))
    <p class="bg-warning">Created between the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>
    @else
    <p>created in all time periods</p>
    @endif
    <p><a href="{{route('branchdashboard.show', $branch->id)}}">Return To Branch Dashboard</a></p>
    <div class="row" style="margin-bottom:10px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._branchselector')
            @include('livewire.partials._search', ['placeholder'=>'Search leads'])
        </div>
    </div>
    
    <div class="row" style="margin-bottom:10px">
        <div class="col form-inline">
            <i class="fas fa-filter text-danger"></i>
            <label>Lead Created</label>
            @include('livewire.partials._periodselector', ['all'=>true])
            <label>Customer / Lead: </label>
            <select wire:model="type" class="form-control" title="With or Without Opportunities">
                @foreach ($types as $key)
                    <option value="{{$key}}">{{$key}}</option>
                @endforeach
            </select>
            <label>With / Without Opportunities</label>
             <div class="input-group-prepend">
                <span class="input-group-text h-38" title="With or Without Opportunities">
                    <i class="fas fa-funnel-dollar"></i>
                </span>
            </div>
             <select wire:model="withOps" class="form-control" title="With or Without Opportunities">
                @foreach ($opstatus as $key)
                    <option value="{{$key}}">{{$key}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row" style="margin-bottom:10px">
        <div class="col form-inline">
            <label>Lead Source </label>
            <select wire:model="lead_source_id" 
            class="form-control" 
            title="Lead Source">
                <option value="All">All</option>
                @foreach ($leadsources as $key=>$source)
                    <option value="{{$key}}">{{$source}}</option>
                @endforeach
            </select>
        </div>
        @if(count($campaigns)> 0)
            <div class="col form-inline">
                <label>Sales Campaign </label>
                <select wire:model="campaign_id" 
                class="form-control" 
                title="Lead Source">
                    <option value="All">All</option>
                    @foreach ($campaigns as $key=>$source)
                        <option value="{{$key}}">{{$source}}</option>
                    @endforeach
                </select>
                
            </div>
        @endif
       <div wire:loading>
            <div class="spinner-border text-danger"></div>
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
                        
                    </td>

                    <td>{{$lead->street}}</td>
                    <td>{{$lead->city}}</td>
                    <td>{{$lead->state}}</td>
                    <td>
                        @if($lead->leadsource)
                         {{$lead->leadsource->source}}
                        @endif
                    </td>
        
       
        
            @if($branch->currentcampaigns->count())
            <td>       
                @foreach ($lead->currentcampaigns as $campaign)
                   
                    <li>
                        <a href="{{route('branchcampaign.show', [$campaign->id, $branch->id])}}">
                            {{$campaign->title}}
                        </a>
                    </li>
                   
                @endforeach 
            
            @if ($branch->currentopencampaigns->count() && in_array(auth()->user()->id, $branch->manager->pluck('user_id')->toArray()))
                
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
        @if(in_array(auth()->user()->id, $branch->manager->pluck('user_id')->toArray()))
        <td>
       
            
            <a 
                data-href="{{route('branchleads.destroy',$lead->assignedToBranch->where('id', $branch->id)->first()->pivot->id)}}" 
                data-toggle="modal" 
                data-target="#delete-lead" 
                data-title = "  {{$lead->businessname}} lead from your branch" 
                href="#"
                title="Delete lead from your branch"><i class="fas fa-trash-alt text-danger"></i>
            </a>
            @if(! $lead->isCustomer)
                <a wire:click="changeCustomer({{ $lead->id }})"
                    title= "Mark lead as customer">
                    <i class="far fa-check-circle text-success"></i>
                </a>
            @else 
                <a wire:click="changeCustomer({{ $lead->id }})" 
                    title= "Mark customer as lead">
                    <i class="far fa-times-circle text-danger"></i>
                </a>
            @endif
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
