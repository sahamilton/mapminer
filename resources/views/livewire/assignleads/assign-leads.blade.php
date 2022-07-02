<div>
    <h2>Search for closest {{$view}} within {{$distance}} miles of Lead </h2>
    <div class="col-sm-10 form-group">
        <form class="form-inline" wire:submit.prevent="updateAddress">
            
            <label for="address">Paste lead address:</label>
            <input class="form-control mx-4" 
                wire:model.defer="address"
                type="text" 
                value="{{$address ? $address : 'Enter an address'}}"
                />
           
            <x-form-select class="mx-4" name="distance" wire:model="distance" label=" within " :options="$distances" /> 
            <button title="Search from an address" type="submit" class="btn btn-success">
                    <i class="fas fa-search"></i>
            </button> 
        </form>
    </div>
    <div class="col-sm-10 form-group">
       <form class="form-inline">
            <x-form-select name="view" wire:model="view" label="Change view:" :options="$views" />
        </form>
    </div>     
    


    <div class="row">
        @if($view === 'branch')
            @include('livewire.assignleads.branch-view')
        @else
            @include('livewire.assignleads.people-view')
        @endif
    </div>         
    
    
    @if($leads->count())
    <div class="row">
        <h4>Branch Leads within {{$leaddistance}} miles of {{$address}}</h4>
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
                            Address
                            @include('includes._sort-icon', ['field' => 'street'])
                        </a>
                    </th>
                    <th>Assigned To Branch</th>
                    <th>
                        <a wire:click.prevent="sortBy('state')" role="button" href="#">
                            Source
                            @include('includes._sort-icon', ['field' => 'lead_source_id'])
                        </a>
                       
                    </th>
                    
                    
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
                    <th>Distance</th>
                
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)

                    <tr>
                        <td>
                            @if($lead->isCustomer)
                                
                                <i class="far fa-check-circle text-success" title="Is a customer"></i>
                               
                           
                            @endif
                           
                            <a href="{{route('address.show',$lead->id)}}">
                                {{$lead->businessname}}
                            </a>
                            
                                (<em>{{$lead->company ? $lead->company->companyname : ''}}</em>)

                            
                            
                        </td>

                        <td>{{$lead->fullAddress()}}</td>
                        <td>
                            @foreach ($lead->assignedTobranch as $branch)

                                <a href="{{route('branches.show', $branch->id)}}">{{$branch->branchname}}</a>
                            @endforeach
                        </td>
                        <td>
                            @if($lead->leadsource)
                             {{$lead->leadsource->source}}
                            @endif
                        </td>
                        
           
            
                       
                        <td>
                            @if($lead->lastActivity)
                                {{$lead->lastActivity->activity_date}}        
                            @endif  
                        </td>
                        <td>
                            @if($lead->created_at)
                                {{$lead->created_at->format('Y-m-d')}}
                            @endif
                        </td>
                        <td>{{number_format($lead->distance,2)}} miles</td>
                    </tr>
                
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
