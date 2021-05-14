<div>
   
    <h4>{{$accounttypes[$accounttype]}} Companies</h4>
    <div class="row mb-4 ">
        @include('livewire.partials._search', ['placeholder'=>'Search Companies'])
    </div>
    
    <div class="row mb-4 ">
        <div class="col form-inline">
           <label for="distance">Within&nbsp;</label>
           <select wire:model="distance"  
                class="form-control">>
                @foreach ($distances as $distance)
                    <option value="{{$distance}}">{{$distance}} miles</option>
                @endforeach
            </select>
             &nbsp;of address &nbsp;
             <form wire:submit.prevent="updateAddress">
                <input class="form-control" 
                    wire:model="address"
                    type="text" 
                    value="{{$address ? $address : 'Enter an address'}}"
                    />
                    <button title="Search from an address" type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i>
                    </button>
            </form>
        </div>
    </div>
    <button class="btn btn-success" 
    title="Export to Excel"
    wire:click='export'>Export <i class="far fa-file-excel"></i></button>
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <label for="accounttype">Account Type:</label>
            <select wire:model="accounttype" 

            class="form-control">
                
                @foreach ($accounttypes as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
       
    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>  
                <a wire:click.prevent="sortBy('companyname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'companyname'])
                </a>
                   
            </th>

            <th>  
                <a wire:click.prevent="sortBy('locations_count')" role="button" href="#">
                    Locations
                    @include('includes._sort-icon', ['field' => 'locations_count'])
                </a>
            </th>
            <th>  
                <a title="Number of locations assigned to any branch" wire:click.prevent="sortBy('assigned')" role="button" href="#">
                    Assigned Locations
                    @include('includes._sort-icon', ['field' => 'assigned'])
                </a>
            </th>
            <th>  
               
                    Manager
                   
            </th>
            <th>  
               
                    Last Updated
                   
            </th>
        </thead>
        <tbody>
            @foreach ($companies as $company)
        
            <tr>
                <td><a href="{{route('company.show', $company->id)}}" title="Explore {{$company->companyname}}">{{$company->companyname}}</a></td>
                <td class='text-center'>{{$company->locations_count}}</td>
                <td class='text-center'>{{$company->assigned}}</td>
                <td class='text-right'>{{$company->managedBy ? $company->managedBy->fullName() : ''}}</td>
                 <td>{{$company->lastUpdated ? $company->lastUpdated->created_at->format('Y-m-d'): ''}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $companies->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $companies->firstItem() }} to {{ $companies->lastItem() }} out of {{ $companies->total() }} results
        </div>
    </div>

</div>