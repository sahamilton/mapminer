<div>
    <h2> @if($leadtype != 'all')
            {{$leadtypes[$leadtype]}}
        @else 
            Nearby Locations
        @endif
        
        @if($company_ids != 'all') 
            of {{$companies[$company_ids]}}
         @endif 
    </h2>
    <h4>
        
    within {{$distance}} miles of {{$address}}</h4>
    
    
  
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Companies'])    
            
            
            <x-form-select 
                wire:model="company_ids"
                name="company_ids"
                label="Locations of Company:"
                :options='$companies'
             />
            
        </div>
       
    </div>
    
    

    <div class="row mb-4">
        <x-form-select wire:model="leadtype"
                name='leadtype'
                label="Lead type:"
                :options='$leadtypes'
                />
        <div class="col form-inline">
            <x-form-select wire:model="distance"
                name='distance'
                label="With locations within:"
                :options='$distances'
                />

             &nbsp;of address &nbsp;
             
            <form wire:submit.prevent="updateAddress">
                <input class="form-control" 
                    wire:model.defer="address"
                    type="text" 
                    value="{{$address ? $address : 'Enter an address'}}"
                    />
                    <button title="Search from an address" type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i>
                    </button>
            </form>


        </div>
    
    
    </div>
    
    <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Company</th>
            <th>
                <a wire:click.prevent="sortBy('businessname')" 
                role="button" href="#" 
                wire:loading.class="bg-danger">
                    Business
                    @include('includes._sort-icon', ['field' => 'businessname'])
                </a>
            </th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>
            
            <th>Number of Contacts</th>
            <th>Created / Updated</th>
            <th>Assigned to Branch</th>
            <th>
                <a wire:click.prevent="sortBy('distance')" 
                role="button" href="#" 
                wire:loading.class="bg-danger">Distance
                @include('includes._sort-icon', ['field' => 'distance'])
            </th>
        </thead>
        <tbody>
        @foreach ($locations as $location)
          
            <tr>
               <td>
                @if($location->company)
                    <a href="{{route('company.show', $location->company_id)}}">
                            
                        {{$location->company->companyname}}
                    </a>
               

                @endif
                </td>
               <td>
                    <a href="{{route('address.show', $location->id)}}">
                        {{$location->businessname}}
                    </a>
                
               </td>
               <td>{{$location->street}}</td>
               <td>{{$location->city}}</td>
               <td>{{$location->state}}</td>
               <td>{{$location->zip}}</td>
               <td>{{$location->contacts_count}}</td>
               <td>{{max($location->created_at, $location->updated_at)->format('Y-m-d')}}</td>
               <td>
                
                    @foreach($location->assignedToBranch as $branch)
                       
                            <a href="{{route('branches.show', $branch->id)}}" title="Visit {{$branch->branchname}}">{{$branch->branchname}}
                            </a>
                        
                        {{! $loop->last ? ',' :''}}
                    @endforeach
                </td>
                <td>{{number_format($location->distance,2)}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
    <div class="row">
        <div class="col">
            {{ $locations->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $locations->firstItem() }} to {{ $locations->lastItem() }} out of {{ $locations->total() }} results
        </div>
    </div>
</div>
