<div>
    <h2>Search Closest Branch within {{$distance}} miles of Lead </h2>
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


    <div class="row">
        <table 
            class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>
                    <a wire:click.prevent="sortBy('branchname')" role="button" href="#">
                        Branch
                        @include('includes._sort-icon', ['field' => 'branchname'])
                    </a>
                    
                </th>

               
              
                <th>Branch Address</th>
                
               
                <th>Manager</th>
                <th>
                    <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                        Distance
                     @include('includes._sort-icon', ['field' => 'distance'])
                    </a>
                </th>
            

               
            </thead>
            <tbody>
                @foreach($branches as $branch)
                    <tr>  
                        <td>
                            <a href="{{route('branches.show',$branch->id)}}" 
                             title="See details of branch {{$branch->branchname}}">
                                {{$branch->branchname}}
                            </a>
                        </td>
                        
                        

                        

                        <td>{{$branch->fullAddress()}}</td>


                        <td>            
                                @if($branch->manager->count()>0)
                                    
                                    @foreach ($branch->manager as $manager)
                                    <a href="{{route('managed.branch',$manager->id)}}"
                                    title="See all branchesmanaged by {{$manager->fullName()}}">
                                    {{$manager->fullName()}}</a>

                                    @endforeach
                                @endif
                        </td>
                        <td>
                            
                                {{$branch->distance ? number_format($branch->distance,1). ' miles' :''}}
                        </td>
                        

                    </tr>
                @endforeach
            </tbody>
        </table>

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

                                {{$branch->branchname}}
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
