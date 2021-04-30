<div>
    <h4>{{isset($company_ids[0]) ? 'Selected': 'All'}} Companies</h4>

    
    <div class="row mb-4 ">
        
        
        <div class="col form-inline">
           <label for="distance">With locations within&nbsp;</label>
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
    <div class="col form-inline">
        <label for="company_ids">Companies:</label>
        <select wire:model="company_ids" 
        multiple
        class="form-control">
            <option>All</option>
            @foreach ($companies as $key=>$value)
                <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success" 
    title="Export to Excel"
    wire:click='export'>Export <i class="far fa-file-excel"></i></button>
    <div class="row mb-4 ">
        @include('livewire.partials._perpage')
        @include('livewire.partials._search', ['placeholder'=>'Search Companies'])
       
    </div>
    <div wire:loading>
            <div class="spinner-border"></div>
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
               <td>{{$location->company->companyname}}</td>
               <td>{{$location->businessname}}</td>
               <td>{{$location->street}}</td>
               <td>{{$location->city}}</td>
               <td>{{$location->state}}</td>
               <td>{{$location->zip}}</td>
               
               <td>
                    @foreach($location->assignedToBranch as $branch)
                        {{$branch->branch_id}}
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
