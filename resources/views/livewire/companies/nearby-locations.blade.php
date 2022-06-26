<div>
    <h2>Locations
       
        
        @if($company_ids != 'all') 
            of {{$companies[$company_ids]}}
         @endif 
    </h2>
    <h4>
     not assigned to any branch    
    within {{$distance}} miles of {{$address}}</h4>
    
    
  
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Companies'])    
            
            
            
            
        </div>
       
    </div>
    
    

    <div class="row mb-4">
       
        <div class="col form-inline">
            <x-form-select wire:model="distance"
                name='distance'
                label="With locations within:"
                :options='$distances'
                />

             &nbsp;of address &nbsp;
             
            <form wire:submit.prevent="updateAddress">
                <input class="form-control" 
                    required
                    wire:model.defer="address"
                    required
                    type="text" 
                    value="{{$address ? $address : 'Enter an address'}}"
                    />
                    <button title="Search from an address" type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i>
                    </button>
            </form>
            <x-form-select wire:model="company_ids"
                name="company_ids"
                label=" belonging to "
                :options="$companies"
                />

        </div>
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    
    </div>
    
    
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Company</th>
            <th>
                <a wire:click.prevent="sortBy('businessname')" 
                role="button" href="#" 
                >
                    Business
                    @include('includes._sort-icon', ['field' => 'businessname'])
                </a>
            </th>
            <th>Address</th>
            
            <th>Source</th>
            <th>Number of Contacts</th>
            <th>
                <a wire:click.prevent="sortBy('created_at')" 
                role="button" href="#" 
                >
                Created
                 @include('includes._sort-icon', ['field' => 'created_at'])
                </a>
            </th>
            
            <th>
                <a wire:click.prevent="sortBy('distance')" 
                role="button" href="#" 
                >Distance
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
               <td>{{$location->fullAddress()}}</td>
               <td>{{$location->leadsource->source}}</td>
               <td>{{$location->contacts_count}}</td>
               <td>{{max($location->created_at, $location->updated_at)->format('Y-m-d')}}</td>
               
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
