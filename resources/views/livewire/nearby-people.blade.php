<div>
    <h2> @if($roletype != 'all')
            {{$roles[$roletype]}}s
        @else 
            All People
        @endif
        
        
    </h2>
    
    @if($distance !='all')    
   <h4> within {{$distance}} miles of {{$address}}</h4>
    @endif

    
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search People'])    
            
            
            
            
        </div>
       
    </div>
    
    

    <div class="row mb-4">
        <x-form-select wire:model="roletype"
                name='roletype'
                label="Roles:"
                :options='$roles'
                />
        <div class="col form-inline">
            <x-form-select wire:model="distance"
                name='distance'
                label="located within:"
                :options='$distances'
                />

             <span class="ml-x" >miles of address </span>
             
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
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    
    </div>
    
    
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>

            <th>
                <a wire:click.prevent="sortBy('lastname')" 
                role="button" href="#" 
                >
                    Person
                    @include('includes._sort-icon', ['field' => 'lastname'])
                </a>
            </th>
            <th>Role(s)</th>
            <th>Reports To</th>
            <th>Address</th>
            @if(auth()->user()->hasRole(['sales_ops', 'admin']))
                   
                <th>
                
                    <a wire:click.prevent="sortBy('created_at')" 
                        role="button" href="#" >
                            Mapminer User Since

                        @include('includes._sort-icon', ['field' => 'created_at'])
                    </a>
                </th>
                
                <th>
                    <a wire:click.prevent="sortBy('lastlogin')" 
                        role="button" href="#" >
                           Last Login

                        @include('includes._sort-icon', ['field' => 'lastlogin'])
                    </a>
                </th>
            @endif

            <th>Assigned to Branch(es)</th>
            <th>
                <a wire:click.prevent="sortBy('distance')" 
                role="button" href="#" 
                >Distance
                @include('includes._sort-icon', ['field' => 'distance'])
            </th>
        </thead>
        <tbody>
        @foreach ($people as $person)
           
            <tr>
               
               <td>
                    @can('manage_users')

                        <a href="{{route('person.details', $person->id)}}">{{$person->fullName()}}</a>
                    @else
                        {{$person->fullName()}}
                    @endcan
                
               </td>
               <td>
                    @if($person->userdetails)
                        @foreach($person->userdetails->roles as $role)
                           
                               {{$role->display_name}}
                                
                            
                            {{! $loop->last ? ',' :''}}
                        @endforeach
                    @endif
                </td>
                <td>{{$person->reportsTo->count() ? $person->reportsTo->fullName() : ''}}
               <td>{{$person->fullAddress()}}</td>
               @if(auth()->user()->hasRole(['sales_ops', 'admin']))
                   <td>{{$person->created_at->format('Y-m-d')}}</td>
                   <td>{{$person->userdetails && $person->userdetails->lastlogin ? $person->userdetails->lastlogin->format('Y-m-d') : ''}}</td>
               @endif
               <td>
                
                    @foreach($person->branchesServiced as $branch)
                       
                            <a href="{{route('branches.show', $branch->id)}}" title="Visit {{$branch->branchname}}">{{$branch->branchname}}
                            </a>
                        
                        {{! $loop->last ? ',' :''}}
                    @endforeach
                </td>
                <td>{{number_format($person->distance,2)}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
    <div class="row">
        <div class="col">
            {{ $people->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $people->firstItem() }} to {{ $people->lastItem() }} out of {{ $people->total() }} results
        </div>
    </div>
</div>
<div>
    {{-- Stop trying to control. --}}
</div>
