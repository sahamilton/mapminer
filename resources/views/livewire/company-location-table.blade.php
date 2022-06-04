<div>

    <h2>{{$company->companyname}} locations 
        @if($state !='all') 
        in {{$state}}
        @endif

    </h2>
    @if($distance != 'any')
    <h4>within {{$distance}} miles of {{$person->fullAddress()}}</h4>
    @endif
    @if($company->salesnotes->count() >0)
        <p>
            <i>See how to sell to <a href="{{route('salesnotes.show', $company->id)}}">{{$company->companyname}}"</a>
            </i>
        </p>
    @endif
    @if($claimed != 'All')
        <p>{{ucwords($claimed)}} by Branches</p>

    @endif
       
    <div class="my-4 row" >
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Locations'])
        </div>
    </div>
    <div class="mb-4 row">
        <div class="col form-inline">
            <i class="fas fa-filter text-danger"></i>
            <x-form-select  class="mx-4" wire:model='state'  name="distance" label='State:' :options='$allstates' />

            <x-form-select  class="mx-4" wire:model='claimed'  name="distance" label='Claimed:' :options='$status' />
           
            <x-form-select  class="mx-4" wire:model='myBranch'  name="myBranch" label='My Leads:' :options='$owned' />
            
            <x-form-select  class="mx-4" wire:model='distance'  name="distance" label='Distance' :options='$distances' />
        </div>
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>

     </div>   

    <div class="row" style="margin-top:5px">
        <table 
            class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>
                    <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                        Business Name
                        @include('includes._sort-icon', ['field' => 'businessname'])
                    </a>
                    
                </th>
                <th>Address</th>

                <th>
                    <a wire:click.prevent="sortBy('city')" role="button" href="#">
                        City
                        @include('includes._sort-icon', ['field' => 'city'])
                    </a>
                </th>
                <th><a wire:click.prevent="sortBy('state')" role="button" href="#">
                        State
                        @include('includes._sort-icon', ['field' => 'state'])
                    </a>
                </th>
                <th>Assigned to Branch</th>
                <th>
                    <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                        Distance from you
                        @include('includes._sort-icon', ['field' => 'distance'])
                    </a>
                </th>
               
            </thead>
            <tbody>
                @include('companies.partials._locationstable')
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
